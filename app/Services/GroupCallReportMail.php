<?php

namespace App\Services;

use App\Http\Controllers\CallReportController;
use App\Models\Logins;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails the "C&M Count" group report (dashboard/group/senior/mail/chart) to the admin-chosen
 * recipients at the admin-chosen Kolkata clock hours. The report itself is untouched: its
 * controller method is called as-is and its data is laid out the same way as the page.
 * Everything is stored in the database (no JSON/files).
 */
class GroupCallReportMail
{
    public const TZ = 'Asia/Kolkata';
    private const CACHE_KEY = 'call_report_mail_recipients';

    /** Same time slots / merged fields as resources/views/user/seniorgroupmailchart.blade.php */
    public const SLOTS = [
        ['title' => '8:00pm - 9:00pm',    'fields' => ['t8to9am', 't9to10am', 't10to11am']],
        ['title' => '9:00pm - 10:00pm',   'fields' => ['t11to12pm']],
        ['title' => '10:00pm - 11:00pm',  'fields' => ['t12to1pm']],
        ['title' => '11:00pm - 12:00am',  'fields' => ['t1to2pm']],
        ['title' => '12:00am - 1:00am',   'fields' => ['t2to3pm']],
        ['title' => '1:00am - 2:00am',    'fields' => ['t3to4pm']],
        ['title' => '2:00am - 3:00am',    'fields' => ['t4to5pm']],
        ['title' => '3:00am - 4:00am',    'fields' => ['t5to6pm']],
        ['title' => '4:00am - 5:00am',    'fields' => ['t6to7pm', 't7to8pm']],
        ['title' => 'Total C&M Count',    'fields' => [
            't8to9am', 't9to10am', 't10to11am', 't11to12pm', 't12to1pm', 't1to2pm',
            't2to3pm', 't3to4pm', 't4to5pm', 't5to6pm', 't6to7pm', 't7to8pm',
        ]],
    ];

    // ---------------------------------------------------------------- recipients

    /** ['to' => [...], 'cc' => [...]] */
    public static function recipients(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                $rows = DB::table('call_report_mail_recipients')->orderBy('id')->get(['type', 'email']);

                return [
                    'to' => $rows->where('type', 'to')->pluck('email')->values()->all(),
                    'cc' => $rows->where('type', 'cc')->pluck('email')->values()->all(),
                ];
            });
        } catch (\Throwable $e) {
            Log::error('Call report mail recipients unreadable (migrated?): ' . $e->getMessage());

            return ['to' => [], 'cc' => []];
        }
    }

    public static function saveRecipients(array $to, array $cc): void
    {
        $to = array_values(array_unique($to));
        $cc = array_values(array_unique(array_diff($cc, $to)));

        DB::transaction(function () use ($to, $cc) {
            DB::table('call_report_mail_recipients')->delete();
            $now = now();
            $rows = [];
            foreach ($to as $e) {
                $rows[] = ['type' => 'to', 'email' => $e, 'created_at' => $now, 'updated_at' => $now];
            }
            foreach ($cc as $e) {
                $rows[] = ['type' => 'cc', 'email' => $e, 'created_at' => $now, 'updated_at' => $now];
            }
            if ($rows) {
                DB::table('call_report_mail_recipients')->insert($rows);
            }
        });
        Cache::forget(self::CACHE_KEY);
    }

    // ---------------------------------------------------------------- hours (Kolkata clock)

    /** @return int[] hours 0-23 */
    public static function hours(): array
    {
        try {
            return DB::table('call_report_mail_hours')->orderBy('hour')->pluck('hour')->map(fn ($h) => (int) $h)->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function saveHours(array $hours): void
    {
        $hours = collect($hours)->map(fn ($h) => (int) $h)->filter(fn ($h) => $h >= 0 && $h <= 23)->unique()->sort()->values();

        DB::transaction(function () use ($hours) {
            DB::table('call_report_mail_hours')->delete();
            $now = now();
            if ($hours->isNotEmpty()) {
                DB::table('call_report_mail_hours')->insert(
                    $hours->map(fn ($h) => ['hour' => $h, 'created_at' => $now, 'updated_at' => $now])->all()
                );
            }
        });
    }

    // ---------------------------------------------------------------- report data

    /**
     * The same data the report page shows: calls the controller method unchanged and
     * lays it out per time slot / team, with "ab" for juniors who have not logged in today.
     */
    public static function report(): array
    {
        $view = app(CallReportController::class)->seniorgroupmailchart(Request::create('/', 'GET'));
        $data = $view->getData();
        $seniors = $data['seniors'];

        $juniorIds = $seniors->flatMap(fn ($s) => $s->juniors->pluck('id'))->unique()->values()->all();
        $loggedToday = $juniorIds
            ? Logins::whereIn('user_id', $juniorIds)->whereDate('logged_in_at', Carbon::today())->pluck('user_id')->unique()->flip()
            : collect();

        $sections = [];
        foreach (self::SLOTS as $slot) {
            $teams = [];
            foreach ($seniors as $senior) {
                $rows = [];
                foreach ($senior->juniors as $junior) {
                    $count = 0;
                    foreach ($slot['fields'] as $field) {
                        $count += ($junior->{$field} ?? 0);
                    }
                    $rows[] = ['name' => $junior->name, 'value' => $loggedToday->has($junior->id) ? $count : 'ab'];
                }
                $teams[] = ['name' => $senior->name, 'rows' => $rows];
            }
            $sections[] = ['title' => $slot['title'], 'teams' => $teams];
        }

        return [
            'date'     => Carbon::parse($data['selectedDate'])->format('d-m-Y'),
            'sections' => $sections,
        ];
    }

    /** Plain-text copy, laid out like the page's "Copy" button text. */
    public static function text(array $report, string $sentAt): string
    {
        $out = "C&M Count Report\nDate: {$report['date']}\nSent: {$sentAt}\n\n";
        foreach ($report['sections'] as $section) {
            $out .= "C&M Count\nDate: {$report['date']}\n{$section['title']}\n";
            foreach ($section['teams'] as $team) {
                $out .= "\nTeam - {$team['name']}\n....................................\n";
                if ($team['rows']) {
                    foreach ($team['rows'] as $r) {
                        $out .= "{$r['name']} - {$r['value']}\n";
                    }
                } else {
                    $out .= "No juniors assigned.\n";
                }
                $out .= "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n";
            }
            $out .= "\nThe above call numbers include only the \"C&M\" counts.\n\n";
        }

        return $out;
    }

    // ---------------------------------------------------------------- sending

    /** Used by the scheduler: the Kolkata hour that is due now, or null. */
    public static function dueSlot(): ?array
    {
        $now = now(self::TZ);
        $hour = (int) $now->format('G');
        if (!in_array($hour, self::hours(), true)) {
            return null;
        }
        $date = $now->toDateString();

        $done = DB::table('call_report_mail_logs')
            ->where('report_date', $date)->where('report_hour', $hour)->where('trigger', 'schedule')
            ->selectRaw("SUM(status = 'sent') as sent, SUM(status = 'failed') as failed")
            ->first();
        if (($done->sent ?? 0) > 0 || ($done->failed ?? 0) >= 3) {
            return null; // already sent this hour, or gave up after 3 failures
        }

        return ['date' => $date, 'hour' => $hour];
    }

    /** @return array{status:string,message:string} */
    public static function send(string $trigger, string $date, int $hour): array
    {
        $r = self::recipients();
        $to = $r['to'];
        $cc = $r['cc'];
        $smtp = config('mail.mailers.smtp', []);
        $username = $smtp['username'] ?? null;

        if (!($to || $cc)) {
            return self::log($trigger, $date, $hour, 'skipped', '', 'No recipient saved on the Call Report Mail page');
        }
        if (!$username) {
            return self::log($trigger, $date, $hour, 'skipped', '', 'SMTP username (MAIL_USERNAME) is empty - set it in .env and run php artisan config:clear');
        }
        if (!$to) {
            $to = [array_shift($cc)];
        }
        $all = implode(', ', array_merge($to, $cc));

        try {
            $port = $smtp['port'] ?? null;
            $port = (is_numeric($port) && (int) $port > 0) ? (int) $port : 587;
            $enc = $smtp['encryption'] ?? null;
            $enc = in_array($enc, ['tls', 'ssl'], true) ? $enc : null;

            Config::set('mail.mailers.callreport', [
                'transport'  => 'smtp',
                'host'       => $smtp['host'] ?? 'smtp.gmail.com',
                'port'       => $port,
                'encryption' => $enc,
                'username'   => $username,
                'password'   => $smtp['password'] ?? null,
                'timeout'    => 20,
            ]);
            $from = filter_var(config('mail.from.address'), FILTER_VALIDATE_EMAIL) ?: $username;

            $report = self::report();
            $kolkata = now(self::TZ);
            $sentAt = $kolkata->format('h:i A') . ' IST';
            $html = view('emails.call-report', [
                'report'  => $report,
                'slotHour' => Carbon::createFromTime($hour)->format('h:00 A'),
                'sentAt'  => $sentAt,
                'sentDay' => $kolkata->format('l, d F'),
                'logoUrl' => rtrim((string) config('app.url'), '/') . '/assets/images/logo.png',
            ])->render();
            $text = self::text($report, $kolkata->format('l, d F') . ', ' . $sentAt);

            Mail::mailer('callreport')->raw($text, function ($m) use ($to, $cc, $from, $html, $hour) {
                $m->to($to)
                    ->subject('[CRM] C&M Count Report - ' . Carbon::createFromTime($hour)->format('h A') . ' IST')
                    ->from($from, config('app.name', 'CRM'));
                if ($cc) {
                    $m->cc($cc);
                }
                $m->html($html);
            });

            return self::log($trigger, $date, $hour, 'sent', $all, 'Report sent');
        } catch (\Throwable $e) {
            Log::error('Call report mail failed: ' . $e->getMessage());

            return self::log($trigger, $date, $hour, 'failed', $all, mb_substr($e->getMessage(), 0, 500));
        }
    }

    private static function log(string $trigger, string $date, int $hour, string $status, string $recipients, string $message): array
    {
        try {
            DB::table('call_report_mail_logs')->insert([
                'report_date' => $date,
                'report_hour' => $hour,
                'trigger'     => $trigger,
                'status'      => $status,
                'recipients'  => $recipients,
                'message'     => $message,
                'sent_at'     => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Call report mail status could not be saved: ' . $e->getMessage());
        }

        return ['status' => $status, 'message' => $message];
    }
}
