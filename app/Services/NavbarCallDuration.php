<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/** Fresh 6pm-6am navbar totals without changing the legacy upload/scheduler summaries. */
class NavbarCallDuration
{
    private const IST = 'Asia/Kolkata';
    private const PBX_TZ = 'America/New_York';
    private const SLOT_TITLES = ['6-7pm', '7-8pm', '8-9pm', '9-10pm', '10-11pm', '11pm-12am', '12-1am', '1-2am', '2-3am', '3-4am', '4-5am', '5-6am'];

    public static function pill($user): ?array
    {
        if (($user->role ?? '') !== 'junior') {
            return null;
        }

        try {
            $ext = trim((string) ($user->phone ?? ''));
            if ($ext === '' || !ctype_digit($ext) || strlen($ext) >= 7) {
                return ['value' => '--', 'tag' => 'No ext', 'state' => 'none', 'title' => 'No extension number is saved for you.'];
            }
            $now = now(self::IST);
            $shift = $now->hour >= 18 ? $now->toDateString() : $now->copy()->subDay()->toDateString();
            $start = Carbon::parse("{$shift} 18:00:00", self::IST);
            $end = $start->copy()->addHours(12);
            $bounds = [];
            for ($hour = 0; $hour <= 12; $hour++) {
                $bounds[] = $start->copy()->addHours($hour)->setTimezone(self::PBX_TZ)->format('Y-m-d H:i:s');
            }
            // Same ownership rule as the Group/Total Duration report: extension caller first,
            // otherwise the called extension. Internal calls are not credited to both users.
            $query = DB::table('call_duration_records')
                ->where('call_date', '>=', $bounds[0])->where('call_date', '<', $bounds[12])
                ->where(function ($query) use ($ext) {
                    $query->where('source', $ext)->orWhere(function ($incoming) use ($ext) {
                        $incoming->where('destination', $ext)
                            ->whereRaw("NOT (CHAR_LENGTH(source) < 7 AND source REGEXP '^[0-9]+$')");
                    });
                });
            // The WHERE already fixes the full shift. Cumulative upper bounds need one
            // timestamp comparison instead of two per hourly sum; differences recover each slot.
            for ($slot = 0; $slot < 11; $slot++) {
                $query->selectRaw(
                    "COALESCE(SUM(CASE WHEN call_date < ? THEN call_duration ELSE 0 END), 0) as prefix{$slot}",
                    [$bounds[$slot + 1]]
                );
            }
            $query->selectRaw('COALESCE(SUM(call_duration), 0) as prefix11');
            // Coverage does not depend on an individual call. Read its scalar aggregate in the
            // same database request rather than issuing a second round trip on every page load.
            $coverage = DB::table('call_duration_uploads')
                ->where('covers_from', '<=', $end->format('Y-m-d H:i:s'))
                ->where('covers_to', '>=', $start->format('Y-m-d H:i:s'))->selectRaw('MAX(covers_to)');
            $row = $query->selectSub($coverage, 'covered_until')->first();
            $slots = [];
            $previous = 0;
            for ($slot = 0; $slot < 12; $slot++) {
                $current = (int) ($row->{"prefix{$slot}"} ?? 0);
                $slots[] = $current - $previous;
                $previous = $current;
            }

            // Coverage is already stored in IST by the existing upload page.
            $lastCovered = $row->covered_until ?? null;
            $covered = $lastCovered ? Carbon::parse($lastCovered, self::IST)->min($end) : null;
            $dueEnd = $now->copy()->subMinutes(60)->startOfHour()->min($end);
            $reaches = fn (Carbon $time) => $covered && $covered->copy()->addMinutes(15)->gte($time);
            if ($dueEnd->lte($start)) {
                $state = 'delay';
            } elseif ($reaches($dueEnd)) {
                $state = $reaches($end) ? 'final' : 'delay';
            } else {
                $state = 'none';
            }
            $parts = [];
            foreach ($slots as $slot => $seconds) {
                if ($seconds > 0) {
                    $parts[] = self::SLOT_TITLES[$slot] . ' ' . self::hms($seconds);
                }
            }

            return [
                'value' => self::hms(array_sum($slots)),
                'tag' => ['delay' => '1h delay', 'none' => 'No data', 'final' => 'Final'][$state],
                'state' => $state,
                'title' => trim('Your call duration, shift of ' . $start->format('d M') . ' (6pm-6am IST). The call data arrives about 1 hour late. '
                    . ($covered ? 'Data up to ' . $covered->format('h:i A') . ' IST. ' : 'No call data uploaded for this shift yet. ')
                    . implode(' | ', $parts)),
            ];
        } catch (\Throwable $exception) {
            return null; // Retain the existing graceful fallback when report tables are unavailable.
        }
    }

    private static function hms(int $seconds): string
    {
        return sprintf('%d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    }
}
