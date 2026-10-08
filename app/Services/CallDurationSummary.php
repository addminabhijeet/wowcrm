<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Call duration of a junior for the latest shift (8:00pm - 5:00am IST) for the small pill in the navbar.
 * The numbers are the same as in the report mails and the Group Report page (PBX time US Eastern -> IST hours, extension =
 * users.phone), but precomputed per extension when a PBX sheet is uploaded, so a page load only reads two indexed rows.
 */
class CallDurationSummary
{
    private const PBX_TZ = 'America/New_York';
    private const IST = 'Asia/Kolkata';
    private const MAIL_DELAY_MIN = 60;      // the sheets are expected one hour after the slot (same delay as the slot mails)
    private const TOLERANCE_MIN = 15;       // same slack as the slot readiness check of the mails

    private const SLOT_TITLES = ['8-9pm', '9-10pm', '10-11pm', '11pm-12am', '12-1am', '1-2am', '2-3am', '3-4am', '4-5am'];

    private static function isExtension(string $v): bool
    {
        return $v !== '' && ctype_digit($v) && strlen($v) < 7;
    }

    // ---------------------------------------------------------------- refresh (upload / scheduler)

    /** Rebuilds the rows of one shift (date = IST date of its 8:00pm start) from the stored call records. */
    public static function refresh(string $shiftDate): void
    {
        $start = Carbon::parse("{$shiftDate} 20:00:00", self::IST);
        $end = $start->copy()->addHours(9);

        $rows = DB::table('call_duration_records')
            ->where('call_date', '>=', $start->copy()->setTimezone(self::PBX_TZ)->format('Y-m-d H:i:s'))
            ->where('call_date', '<', $end->copy()->setTimezone(self::PBX_TZ)->format('Y-m-d H:i:s'))
            ->get(['call_date', 'source', 'destination', 'call_duration']);

        $sum = [];   // extension => [slot => seconds]
        foreach ($rows as $r) {
            $source = (string) $r->source;
            $destination = (string) $r->destination;
            $ext = self::isExtension($source) ? $source : (self::isExtension($destination) ? $destination : null);
            if ($ext === null) {
                continue;
            }
            $slot = (Carbon::parse($r->call_date, self::PBX_TZ)->setTimezone(self::IST)->hour - 20 + 24) % 24;
            if ($slot > 8) {
                continue;
            }
            $sum[$ext][$slot] = ($sum[$ext][$slot] ?? 0) + (int) $r->call_duration;
        }

        // how far the uploaded sheets reach inside this shift
        $covered = null;
        foreach (GroupCallReportMail::coverage() as [$from, $to]) {
            if ($to->gte($start) && $from->lte($end)) {
                $cand = $to->gt($end) ? $end : $to;
                if ($covered === null || $cand->gt($covered)) {
                    $covered = $cand;
                }
            }
        }

        $now = now();
        DB::transaction(function () use ($shiftDate, $sum, $covered, $now) {
            DB::table('call_duration_summaries')->where('shift_date', $shiftDate)->delete();

            $batch = [];
            foreach ($sum as $ext => $slots) {
                $row = ['shift_date' => $shiftDate, 'extension' => (string) $ext, 'total' => array_sum($slots), 'created_at' => $now, 'updated_at' => $now];
                for ($i = 0; $i < 9; $i++) {
                    $row["slot{$i}"] = $slots[$i] ?? 0;
                }
                $batch[] = $row;
            }
            foreach (array_chunk($batch, 200) as $chunk) {
                DB::table('call_duration_summaries')->insert($chunk);
            }

            DB::table('call_duration_shifts')->updateOrInsert(
                ['shift_date' => $shiftDate],
                ['covered_until' => $covered ? $covered->format('Y-m-d H:i:s') : null, 'refreshed_at' => $now, 'updated_at' => $now, 'created_at' => $now]
            );
        });
    }

    /** Refreshes every shift that overlaps an uploaded range (IST). */
    public static function refreshRange(Carbon $fromIst, Carbon $toIst): void
    {
        $day = $fromIst->copy()->startOfDay()->subDay();
        $last = $toIst->copy()->startOfDay();
        for ($n = 0; $day->lte($last) && $n < 10; $n++, $day->addDay()) {
            $start = Carbon::parse($day->toDateString() . ' 20:00:00', self::IST);
            if ($start->lte($toIst) && $start->copy()->addHours(9)->gte($fromIst)) {
                self::refresh($day->toDateString());
            }
        }
    }

    /** Scheduler: refreshes the current and the previous shift when a sheet was uploaded since they were last built. */
    public static function refreshStale(): void
    {
        try {
            $lastUpload = DB::table('call_duration_uploads')->max('created_at');
            $today = now(self::IST)->startOfDay();
            foreach ([$today->copy()->subDay()->toDateString(), $today->toDateString()] as $date) {
                $row = DB::table('call_duration_shifts')->where('shift_date', $date)->first(['refreshed_at']);
                if (!$row || ($lastUpload && $row->refreshed_at < $lastUpload)) {
                    self::refresh($date);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Call duration summary refresh failed: ' . $e->getMessage());
        }
    }

    // ---------------------------------------------------------------- the navbar pill

    /**
     * Pill for a junior: ['value' => 'h:mm:ss', 'tag' => '1h delay' | 'No data' | 'Final' | 'No ext', 'state' => ..., 'title' => ...],
     * or null for other roles / when anything goes wrong (a page must never break because of this).
     */
    public static function pill($user): ?array
    {
        if (($user->role ?? '') !== 'junior') {
            return null;
        }

        try {
            $ext = trim((string) ($user->phone ?? ''));
            $now = now(self::IST);
            $shift = $now->hour >= 20 ? $now->toDateString() : $now->copy()->subDay()->toDateString();
            $start = Carbon::parse("{$shift} 20:00:00", self::IST);
            $end = $start->copy()->addHours(9);

            if (!self::isExtension($ext)) {
                return ['value' => '--', 'tag' => 'No ext', 'state' => 'none', 'title' => 'No extension number is saved for you.'];
            }

            $status = DB::table('call_duration_shifts')->where('shift_date', $shift)->first(['covered_until']);
            $covered = $status && $status->covered_until ? Carbon::parse($status->covered_until, self::IST) : null;
            $row = DB::table('call_duration_summaries')->where('shift_date', $shift)->where('extension', $ext)->first();

            // The latest slot whose mail would be due by now (slot end + 1 hour): its data must have arrived
            $dueEnd = $now->copy()->subMinutes(self::MAIL_DELAY_MIN)->startOfHour();
            if ($dueEnd->gt($end)) {
                $dueEnd = $end->copy();
            }
            $reaches = fn (Carbon $t) => $covered && $covered->copy()->addMinutes(self::TOLERANCE_MIN)->gte($t);

            if ($dueEnd->lte($start)) {
                $state = 'delay';                                   // no slot is due yet: only the normal 1 hour delay
            } elseif ($reaches($dueEnd)) {
                $state = $reaches($end) ? 'final' : 'delay';
            } else {
                $state = 'none';                                    // more than one hour passed and the data has not arrived
            }

            $total = (int) ($row->total ?? 0);
            $hms = sprintf('%d:%02d:%02d', intdiv($total, 3600), intdiv($total % 3600, 60), $total % 60);

            $parts = [];
            for ($i = 0; $i < 9 && $row; $i++) {
                $s = (int) $row->{"slot{$i}"};
                if ($s > 0) {
                    $parts[] = self::SLOT_TITLES[$i] . ' ' . sprintf('%d:%02d:%02d', intdiv($s, 3600), intdiv($s % 3600, 60), $s % 60);
                }
            }
            $title = 'Your call duration, shift of ' . $start->format('d M') . ' (8pm-5am IST). The call data arrives about 1 hour late. '
                . ($covered ? 'Data up to ' . $covered->format('h:i A') . ' IST. ' : 'No call data uploaded for this shift yet. ')
                . ($parts ? implode(' | ', $parts) : '');

            return [
                'value' => $hms,   // always the day's total so far (0:00:00 when nothing has arrived yet)
                'tag'   => ['delay' => '1h delay', 'none' => 'No data', 'final' => 'Final'][$state],
                'state' => $state,
                'title' => trim($title),
            ];
        } catch (\Throwable $e) {
            return null;   // tables not migrated yet, or any other problem: show nothing
        }
    }
}
