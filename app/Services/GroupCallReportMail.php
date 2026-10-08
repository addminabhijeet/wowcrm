<?php

namespace App\Services;

use App\Http\Controllers\CallDurationController;
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

    // ---------------------------------------------------------------- per-hour slot mail (added)

    /**
     * Kolkata send hour => index in SLOTS of the slot that ENDS at that hour. The slot titles are IST clock times
     * (8:00pm - 9:00pm is mailed at 21:00 ... 4:00am - 5:00am at 05:00), the same hours the admin ticks.
     */
    public const HOUR_SLOTS = [21 => 0, 22 => 1, 23 => 2, 0 => 3, 1 => 4, 2 => 5, 3 => 6, 4 => 7, 5 => 8];

    private static function mailDone(string $date, int $hour, string $trigger): bool
    {
        $done = DB::table('call_report_mail_logs')
            ->where('report_date', $date)->where('report_hour', $hour)->where('trigger', $trigger)
            ->selectRaw("SUM(status = 'sent') as sent, SUM(status = 'failed') as failed")
            ->first();

        return ($done->sent ?? 0) > 0;   // failed deliveries are retried (see retryAllowed), never given up
    }

    /** Quick attempts (every scheduler run) before a failing delivery is retried only every SLOW_RETRY_MIN minutes. */
    public const FAST_RETRIES = 3;
    public const SLOW_RETRY_MIN = 30;

    /** False while a delivery that failed FAST_RETRIES times is waiting for its next, slower retry. */
    private static function retryAllowed(string $date, int $hour, string $trigger): bool
    {
        $failed = DB::table('call_report_mail_logs')->where('report_date', $date)->where('report_hour', $hour)
            ->where('trigger', $trigger)->where('status', 'failed');
        if ((clone $failed)->count() < self::FAST_RETRIES) {
            return true;
        }
        $last = (clone $failed)->max('sent_at');

        return !$last || Carbon::parse($last, config('app.timezone'))->addMinutes(self::SLOW_RETRY_MIN)->lte(now());
    }

    /**
     * Scheduler entry (every 5 minutes, and right after an upload): slot mails whose time has come are sent only
     * when the uploaded call data covers that slot, otherwise they are held and re-checked. See releaseDue().
     */
    public static function sendDueSlotMails(): void
    {
        $ticked = self::hours();
        if (!$ticked) {
            return;
        }

        $lock = null;
        try {
            $lock = Cache::lock('call_report_release', 900);
            if (!$lock->get()) {
                return;   // another run (scheduler or upload) is releasing right now
            }
        } catch (\Throwable $e) {
            $lock = null;   // cache store without lock support: run unlocked, the send log still prevents repeats
        }

        try {
            self::releaseDue($ticked);
        } finally {
            if ($lock) {
                try {
                    $lock->release();
                } catch (\Throwable $e) {
                    // lock already expired
                }
            }
        }
    }

    /** Same layout/data as report(), but only one slot (one controller field) as a single card. */
    public static function slotReport(array $fields, string $title): array
    {
        $view = app(CallReportController::class)->seniorgroupmailchart(Request::create('/', 'GET'));
        $data = $view->getData();
        $seniors = $data['seniors'];

        $juniorIds = $seniors->flatMap(fn ($s) => $s->juniors->pluck('id'))->unique()->values()->all();
        $loggedToday = $juniorIds
            ? Logins::whereIn('user_id', $juniorIds)->whereDate('logged_in_at', Carbon::today())->pluck('user_id')->unique()->flip()
            : collect();

        $teams = [];
        foreach ($seniors as $senior) {
            $rows = [];
            foreach ($senior->juniors as $junior) {
                $count = 0;
                foreach ($fields as $field) {
                    $count += ($junior->{$field} ?? 0);
                }
                $rows[] = ['name' => $junior->name, 'value' => $loggedToday->has($junior->id) ? $count : 'ab'];
            }
            $teams[] = ['name' => $senior->name, 'rows' => $rows];
        }

        return [
            'date'     => Carbon::parse($data['selectedDate'])->format('d-m-Y'),
            'sections' => [['title' => $title, 'teams' => $teams]],
        ];
    }

    /**
     * Test: renders BOTH templates (slot-only and full) with live data and mails them to $to only.
     * Does not use the saved recipients and writes no send-log row. $dry renders without sending.
     *
     * @return string[] one result line per template
     */
    public static function sendTest(string $to, int $slotIndex, bool $dry = false): array
    {
        $smtp = config('mail.mailers.smtp', []);
        $username = $smtp['username'] ?? null;
        if (!$dry && !$username) {
            return ['SMTP username (MAIL_USERNAME) is empty - set it in .env and run php artisan config:clear'];
        }

        $slot = self::SLOTS[$slotIndex];
        $hour = array_search($slotIndex, self::HOUR_SLOTS, true);
        $hour = $hour === false ? (int) now(self::TZ)->format('G') : (int) $hour;
        $kolkata = now(self::TZ);
        $sentAt = $kolkata->format('h:i A') . ' IST';

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

        $templates = [
            'Slot report' => fn () => [self::slotReport($slot['fields'], $slot['title']), '[TEST] [CRM] C&M Count Report - ' . $slot['title'] . ' IST'],
            'Full report' => fn () => [self::report(), '[TEST] [CRM] C&M Count Report - ' . Carbon::createFromTime($hour)->format('h A') . ' IST (full)'],
        ];

        $out = [];
        foreach ($templates as $name => $build) {
            try {
                [$report, $subject] = $build();
                $html = view('emails.call-report', [
                    'report'  => $report,
                    'slotHour' => Carbon::createFromTime($hour)->format('h:00 A'),
                    'sentAt'  => $sentAt,
                    'sentDay' => $kolkata->format('l, d F'),
                    'logoUrl' => rtrim((string) config('app.url'), '/') . '/assets/images/logo.png',
                ])->render();
                $text = self::text($report, $kolkata->format('l, d F') . ', ' . $sentAt);

                if ($dry) {
                    $out[] = "{$name}: rendered OK (" . count($report['sections']) . ' card(s), ' . strlen($html) . " bytes) - subject: {$subject}";
                    continue;
                }
                Mail::mailer('callreport')->raw($text, function ($m) use ($to, $from, $html, $subject) {
                    $m->to($to)->subject($subject)->from($from, config('app.name', 'CRM'));
                    $m->html($html);
                });
                $out[] = "{$name}: sent to {$to} - subject: {$subject}";
            } catch (\Throwable $e) {
                $out[] = "{$name}: FAILED - " . mb_substr($e->getMessage(), 0, 300);
            }
        }

        return $out;
    }

    /**
     * Emails only the slot that ended at $date $hour (IST), built from the same data as the Group Report page
     * (C&M count + call duration). $opts['durations'] = false sends it without call duration (manual release only).
     */
    public static function sendSlot(string $date, int $hour, string $trigger = 'slot', array $opts = []): array
    {
        $slot = self::slotOf($date, $hour);
        if ($slot === null) {
            return ['status' => 'skipped', 'message' => 'No slot ends at this hour'];
        }
        [$shiftDate, $i, $end] = $slot;
        $withDurations = $opts['durations'] ?? true;
        $title = self::SLOTS[$i]['title'];

        try {
            $report = self::buildReport($shiftDate, $i, $withDurations);
        } catch (\Throwable $e) {
            Log::error('Call report slot mail: report could not be built: ' . $e->getMessage());

            return self::log($trigger, $date, $hour, 'failed', '', mb_substr($e->getMessage(), 0, 500));
        }
        $late = self::minutesLate($end);
        self::addNotes($report, $end, $late, $withDurations);

        return self::deliver(
            $trigger, $date, $hour,
            '[CRM] C&M Count Report - ' . $title . ' IST',
            $report,
            'Slot report sent: ' . $title . ($late > 10 ? " (delayed {$late} min)" : '') . ($withDurations ? '' : ' - without call duration (released by admin)')
        );
    }

    /** The full report (every slot + total) of the shift that ended at $date $hour (05:00 IST), same data as the page. */
    public static function sendFull(string $date, int $hour, string $trigger = 'schedule'): array
    {
        $slot = self::slotOf($date, $hour);
        if ($slot === null) {
            return ['status' => 'skipped', 'message' => 'No shift ends at this hour'];
        }
        [$shiftDate, , $end] = $slot;

        try {
            $report = self::buildReport($shiftDate, null, true);
        } catch (\Throwable $e) {
            Log::error('Call report full mail: report could not be built: ' . $e->getMessage());

            return self::log($trigger, $date, $hour, 'failed', '', mb_substr($e->getMessage(), 0, 500));
        }
        $late = self::minutesLate($end);
        self::addNotes($report, $end, $late, true);

        return self::deliver(
            $trigger, $date, $hour,
            '[CRM] C&M Count Report - ' . Carbon::createFromTime($hour)->format('h A') . ' IST',
            $report,
            'Report sent' . ($late > 10 ? " (delayed {$late} min)" : '')
        );
    }

    /** Minutes past the time the mail was due (the slot end plus MAIL_DELAY_MIN). */
    private static function minutesLate(Carbon $end): int
    {
        return (int) floor((now(self::TZ)->getTimestamp() - self::dueAt($end)->getTimestamp()) / 60);
    }

    /** "Delayed" / "without call duration" lines of the mail (shown by the template only when set). */
    private static function addNotes(array &$report, Carbon $end, int $late, bool $withDurations): void
    {
        if ($withDurations && $late > 10) {
            $report['delayed_note'] = 'Due at ' . self::dueAt($end)->format('h:i A') . ' IST, sent at ' . now(self::TZ)->format('h:i A')
                . ' IST because the call data for this slot was uploaded late.';
        }
        if (!$withDurations) {
            $report['no_duration_note'] = true;
        }
    }

    /**
     * One shift (date = its 8:00pm IST start) as the mail template expects it: one card for $slot, or every slot and
     * the total when $slot is null. Same numbers as the Group Report page (C&M count, "ab", call duration).
     */
    public static function buildReport(string $shiftDate, ?int $slot, bool $withDurations = true): array
    {
        $teams = app(CallDurationController::class)->groupData($shiftDate);
        $sections = [];

        foreach ($slot === null ? array_merge(range(0, 8), [null]) : [$slot] as $c) {
            $outTeams = [];
            foreach ($teams as $t) {
                $rows = [];
                foreach ($t['members'] as $m) {
                    $row = ['name' => $m['name'], 'value' => $m['absent'] ? 'ab' : ($c === null ? $m['cm_total'] : $m['cm_slots'][$c])];
                    if ($withDurations && !$m['absent']) {
                        $row['duration'] = CallDurationController::durationText($c === null ? $m['total'] : $m['slots'][$c]);
                    }
                    $rows[] = $row;
                }
                $outTeams[] = ['name' => $t['name'], 'rows' => $rows];
            }
            $sections[] = ['title' => $c === null ? 'Total C&M Count' : self::SLOTS[$c]['title'], 'teams' => $outTeams];
        }

        return ['date' => Carbon::parse($shiftDate)->format('d-m-Y'), 'sections' => $sections, 'duration_note' => $withDurations];
    }

    /** Sends a ready-made $report to the saved recipients (same SMTP, template and send-list log as send()). */
    private static function deliver(string $trigger, string $date, int $hour, string $subject, array $report, string $okMessage): array
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

            $kolkata = now(self::TZ);
            $sentAt = $kolkata->format('h:i A') . ' IST';
            $html = view('emails.call-report', [
                'report'   => $report,
                'slotHour' => Carbon::createFromTime($hour)->format('h:00 A'),
                'sentAt'   => $sentAt,
                'sentDay'  => $kolkata->format('l, d F'),
                'logoUrl'  => rtrim((string) config('app.url'), '/') . '/assets/images/logo.png',
            ])->render();
            $text = self::text($report, $kolkata->format('l, d F') . ', ' . $sentAt);

            Mail::mailer('callreport')->raw($text, function ($m) use ($to, $cc, $from, $html, $subject) {
                $m->to($to)->subject($subject)->from($from, config('app.name', 'CRM'));
                if ($cc) {
                    $m->cc($cc);
                }
                $m->html($html);
            });

            return self::log($trigger, $date, $hour, 'sent', $all, $okMessage);
        } catch (\Throwable $e) {
            Log::error('Call report mail failed: ' . $e->getMessage());

            return self::log($trigger, $date, $hour, 'failed', $all, mb_substr($e->getMessage(), 0, 500));
        }
    }

    // ---------------------------------------------------------------- call duration in the mails (added)

    /**
     * Adds each recruiter's call duration to a report built by report() / slotReport(): $row['duration'] = "h:mm:ss",
     * taken from the same data as Call Duration > Group Report, so the page and every mail show the same numbers.
     * Recruiters marked "ab" get none (same rule as the page). Any failure leaves the report as it was.
     */
    public static function withDurations(array $report): array
    {
        try {
            $date = Carbon::createFromFormat('!d-m-Y', $report['date'])->toDateString();
            $teams = app(CallDurationController::class)->groupData($date);
        } catch (\Throwable $e) {
            Log::error('Call report mail: call durations unavailable: ' . $e->getMessage());

            return $report;
        }

        $byTeam = [];
        foreach ($teams as $t) {
            foreach ($t['members'] as $m) {
                $byTeam[$t['name']][$m['name']] = $m;
            }
        }

        $titles = array_column(array_slice(self::SLOTS, 0, 9), 'title');
        foreach ($report['sections'] as &$section) {
            $slot = array_search($section['title'], $titles, true);   // false = the "Total" card
            foreach ($section['teams'] as &$team) {
                foreach ($team['rows'] as &$row) {
                    $m = $byTeam[$team['name']][$row['name']] ?? null;
                    if ($m === null || $row['value'] === 'ab') {
                        continue;
                    }
                    $row['duration'] = CallDurationController::durationText($slot === false ? $m['total'] : ($m['slots'][$slot] ?? 0));
                }
                unset($row);
            }
            unset($team);
        }
        unset($section);
        $report['duration_note'] = true;

        return $report;
    }

    // ---------------------------------------------------------------- hold until the call data is uploaded (added)

    /** Minutes of slack when comparing an upload's first/last call with a slot's start/end (quiet minutes at the edges). */
    public const COVER_TOLERANCE_MIN = 15;

    /** A slot whose time came longer ago than this is no longer picked up as "newly due" (only already held ones keep waiting). */
    public const REGISTER_WINDOW_HOURS = 12;

    /**
     * Every slot mail goes out this many minutes after its slot ends (the 8:00pm - 9:00pm slot at 10:00pm IST), which gives the
     * PBX sheet one more hour to be exported and uploaded. The data check itself still looks at the slot's own hour.
     */
    public const MAIL_DELAY_MIN = 60;

    /** When the mail of a slot that ends at $end becomes due. */
    public static function dueAt(Carbon $end): Carbon
    {
        return $end->copy()->addMinutes(self::MAIL_DELAY_MIN);
    }

    /** Uploaded call-data ranges in IST, overlapping or (within the tolerance) touching ones merged. @return array<int,array{0:Carbon,1:Carbon}> */
    public static function coverage(): array
    {
        try {
            $rows = DB::table('call_duration_uploads')->orderBy('covers_from')->get(['covers_from', 'covers_to']);
        } catch (\Throwable $e) {
            return [];
        }

        $merged = [];
        foreach ($rows as $r) {
            $from = Carbon::parse($r->covers_from, self::TZ);
            $to = Carbon::parse($r->covers_to, self::TZ);
            $last = count($merged) - 1;
            if ($last >= 0 && $from->lte($merged[$last][1]->copy()->addMinutes(self::COVER_TOLERANCE_MIN))) {
                if ($to->gt($merged[$last][1])) {
                    $merged[$last][1] = $to;
                }
            } else {
                $merged[] = [$from, $to];
            }
        }

        return $merged;
    }

    /** True when one uploaded range contains the whole of $start..$end (give or take the tolerance at both edges). */
    public static function isCovered(Carbon $start, Carbon $end, ?array $coverage = null): bool
    {
        foreach ($coverage ?? self::coverage() as [$from, $to]) {
            if ($from->lte($start->copy()->addMinutes(self::COVER_TOLERANCE_MIN)) && $to->gte($end->copy()->subMinutes(self::COVER_TOLERANCE_MIN))) {
                return true;
            }
        }

        return false;
    }

    /** IST start and end of slot $i of the shift that starts at 8:00pm IST on $shiftDate. @return array{0:Carbon,1:Carbon} */
    public static function slotWindow(string $shiftDate, int $i): array
    {
        $start = Carbon::parse("{$shiftDate} 20:00:00", self::TZ)->addHours($i);

        return [$start, $start->copy()->addHour()];
    }

    /** Shift date (its 8:00pm IST start), slot index and slot end of the slot that ends at $date $hour. @return array{0:string,1:int,2:Carbon}|null */
    private static function slotOf(string $date, int $hour): ?array
    {
        $i = self::HOUR_SLOTS[$hour] ?? null;
        if ($i === null) {
            return null;
        }
        $end = Carbon::parse($date . ' ' . sprintf('%02d:00:00', $hour), self::TZ);

        return [$end->copy()->subHours($i + 1)->toDateString(), $i, $end];
    }

    private static function slotDone(string $date, int $hour): bool
    {
        return self::mailDone($date, $hour, 'slot')
            || DB::table('call_report_mail_logs')->where('report_date', $date)->where('report_hour', $hour)
                ->where('trigger', 'slot')->where('status', 'discarded')->exists();
    }

    private static function markHeld(string $date, int $hour, int $i, string $reason = 'call data'): void
    {
        $exists = DB::table('call_report_mail_logs')->where('report_date', $date)->where('report_hour', $hour)
            ->where('trigger', 'slot')->whereIn('status', ['held', 'sent', 'discarded'])->exists();
        if (!$exists) {
            self::log('slot', $date, $hour, 'held', '', 'Waiting for ' . $reason . ': ' . self::SLOTS[$i]['title']);
        }
    }

    /** False when no recipient is saved or the SMTP username is empty: ready mails then stay held instead of being logged as "not sent" every 5 minutes. */
    private static function canSend(): bool
    {
        $r = self::recipients();

        return ($r['to'] || $r['cc']) && !empty(config('mail.mailers.smtp.username'));
    }

    private static function clearHeld(string $date, int $hour): void
    {
        DB::table('call_report_mail_logs')->where('report_date', $date)->where('report_hour', $hour)
            ->where('trigger', 'slot')->where('status', 'held')->delete();
    }

    /**
     * Sends every slot mail that is due and whose call data has been uploaded; the others stay held (no timeout).
     * Within one shift the slots go out in order, and the full report follows the last slot once the whole shift is covered.
     */
    private static function releaseDue(array $ticked): void
    {
        $now = now(self::TZ);
        $coverage = self::coverage();
        $canSend = self::canSend();
        $pending = [];   // shift date => [slot index => slot end]

        // slots whose time has come (within the last REGISTER_WINDOW_HOURS) and whose hour is ticked; older ones only if already held
        $recent = $now->copy()->subHours(self::REGISTER_WINDOW_HOURS);
        foreach ([-1, 0] as $offset) {
            $x = $now->copy()->startOfDay()->addDays($offset)->toDateString();
            for ($i = 0; $i < 9; $i++) {
                [, $end] = self::slotWindow($x, $i);
                $due = self::dueAt($end);
                if ($due->lte($now) && $due->gte($recent) && in_array((int) $end->format('G'), $ticked, true)) {
                    $pending[$x][$i] = $end;
                }
            }
        }
        // older slots that are still waiting (never cancelled automatically)
        foreach (DB::table('call_report_mail_logs')->where('trigger', 'slot')->where('status', 'held')->get(['report_date', 'report_hour']) as $h) {
            $slot = self::slotOf((string) $h->report_date, (int) $h->report_hour);
            if ($slot !== null) {
                $pending[$slot[0]][$slot[1]] = $slot[2];
            }
        }

        ksort($pending);
        foreach ($pending as $x => $slots) {
            ksort($slots);
            foreach ($slots as $i => $end) {
                $date = $end->toDateString();
                $hour = (int) $end->format('G');

                if (!self::slotDone($date, $hour)) {
                    if (!self::isCovered(self::slotWindow($x, $i)[0], $end, $coverage)) {
                        self::markHeld($date, $hour, $i);
                        break;   // the later slots of this shift wait for it
                    }
                    if (!$canSend) {
                        self::markHeld($date, $hour, $i, 'mail settings (no recipient saved or MAIL_USERNAME empty)');
                        break;
                    }
                    if (!self::retryAllowed($date, $hour, 'slot')) {
                        break;   // a delivery attempt failed several times: wait before trying again
                    }
                    if (self::sendSlot($date, $hour)['status'] === 'sent') {
                        self::clearHeld($date, $hour);
                    } else {
                        self::markHeld($date, $hour, $i, 'mail delivery (the last attempt failed, retrying)');
                        break;   // keep the order: the later slots wait for this one
                    }
                }

                if ($i === 8 && DB::table('call_report_mail_logs')->where('report_date', $date)->where('report_hour', $hour)
                        ->where('trigger', 'slot')->where('status', 'sent')->exists()
                    && !self::mailDone($date, $hour, 'schedule')
                    && $canSend
                    && self::retryAllowed($date, $hour, 'schedule')
                    && self::isCovered(self::slotWindow($x, 0)[0], $end, $coverage)) {
                    self::sendFull($date, $hour);
                }
            }
        }
    }

    /** Slot mails waiting for call data, oldest first. @return array<int,array{date:string,hour:int,shift:string,title:string,since:string}> */
    public static function heldRows(): array
    {
        $out = [];
        foreach (DB::table('call_report_mail_logs')->where('trigger', 'slot')->where('status', 'held')->orderBy('id')->get(['report_date', 'report_hour', 'sent_at']) as $h) {
            $slot = self::slotOf((string) $h->report_date, (int) $h->report_hour);
            if ($slot === null) {
                continue;
            }
            $out[] = [
                'date'  => (string) $h->report_date,
                'hour'  => (int) $h->report_hour,
                'shift' => $slot[0],
                'title' => self::SLOTS[$slot[1]]['title'],
                'index' => $slot[1],
                'since' => Carbon::parse($h->sent_at, config('app.timezone'))->setTimezone(self::TZ)->format('d M, h:i A'),
            ];
        }
        usort($out, fn ($a, $b) => [$a['shift'], $a['index']] <=> [$b['shift'], $b['index']]);

        return $out;
    }

    /** Admin: mails a held slot now, without call duration. The slot counts as sent afterwards. */
    public static function releaseWithoutDurations(string $date, int $hour): array
    {
        $held = DB::table('call_report_mail_logs')->where('report_date', $date)->where('report_hour', $hour)
            ->where('trigger', 'slot')->where('status', 'held')->exists();
        if (!$held) {
            return ['status' => 'skipped', 'message' => 'This slot is not waiting for call data any more.'];
        }

        $result = self::sendSlot($date, $hour, 'slot', ['durations' => false]);
        if ($result['status'] === 'sent') {
            self::clearHeld($date, $hour);
        }

        return $result;
    }

    /**
     * Admin: gives up the slot mails of one shift that are due and not sent yet (they are never sent).
     * @return int number of mails discarded
     */
    public static function discardShift(string $shiftDate): int
    {
        $now = now(self::TZ);
        $n = 0;
        for ($i = 0; $i < 9; $i++) {
            [, $end] = self::slotWindow($shiftDate, $i);
            if (self::dueAt($end)->gt($now)) {
                continue;   // not due yet: it will be handled when its time comes
            }
            $date = $end->toDateString();
            $hour = (int) $end->format('G');
            $rows = DB::table('call_report_mail_logs')->where('report_date', $date)->where('report_hour', $hour)->where('trigger', 'slot');

            if ((clone $rows)->whereIn('status', ['sent', 'discarded'])->exists()) {
                continue;   // already sent, or already discarded
            }
            $message = 'Discarded by admin: call data was never uploaded';
            if ((clone $rows)->where('status', 'held')->exists()) {
                (clone $rows)->where('status', 'held')->update(['status' => 'discarded', 'message' => $message, 'updated_at' => now()]);
            } else {
                self::log('slot', $date, $hour, 'discarded', '', $message);
            }
            $n++;
        }

        return $n;
    }

    /** For the Upload Report page: uploaded ranges, the slots of the latest shift (data / mail state) and the held mails. */
    public static function statusPanel(): array
    {
        $now = now(self::TZ);
        $coverage = self::coverage();
        $shift = $now->hour >= 20 ? $now->toDateString() : $now->copy()->subDay()->toDateString();

        $slots = [];
        for ($i = 0; $i < 9; $i++) {
            [$start, $end] = self::slotWindow($shift, $i);
            $date = $end->toDateString();
            $hour = (int) $end->format('G');
            $mail = DB::table('call_report_mail_logs')->where('report_date', $date)->where('report_hour', $hour)->where('trigger', 'slot')
                ->orderByDesc('id')->value('status');

            $slots[] = [
                'title' => self::SLOTS[$i]['title'],
                'data'  => $end->gt($now) && !self::isCovered($start, $end, $coverage) ? 'upcoming' : (self::isCovered($start, $end, $coverage) ? 'ready' : 'missing'),
                'mail'  => $mail ?: (self::dueAt($end)->gt($now) ? 'not due yet (due ' . self::dueAt($end)->format('h:i A') . ')' : 'not sent'),
            ];
        }

        return [
            'coverage' => array_map(fn ($c) => [$c[0]->format('d M, h:i A'), $c[1]->format('d M, h:i A')], $coverage),
            'shift'    => Carbon::parse($shift)->format('d-m-Y'),
            'slots'    => $slots,
            'held'     => self::heldRows(),
        ];
    }
}
