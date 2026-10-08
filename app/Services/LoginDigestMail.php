<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Grouped login / logout mails. In grouped mode only JUNIOR logins and logouts are stored, and one mail per IST hour slot
 * that had at least one event is sent when the slot ends. Recipients and SMTP are the ones of the existing login alerts
 * (LoginAlertService). When grouping is switched off, or its tables do not exist yet, the listeners below behave exactly like
 * the original ones (one mail per event, everyone except admin).
 */
class LoginDigestMail
{
    public const TZ = 'Asia/Kolkata';

    /** Quick attempts (every scheduler run) before a failing mail is retried only every SLOW_RETRY_MIN minutes. */
    public const FAST_RETRIES = 3;
    public const SLOW_RETRY_MIN = 30;

    // ---------------------------------------------------------------- mode

    public static function grouped(): bool
    {
        try {
            return (bool) DB::table('login_digest_settings')->value('grouped');
        } catch (\Throwable $e) {
            return false;   // tables not migrated yet: keep the original behaviour
        }
    }

    public static function setGrouped(bool $on): void
    {
        DB::transaction(function () use ($on) {
            DB::table('login_digest_settings')->delete();
            DB::table('login_digest_settings')->insert(['grouped' => $on ? 1 : 0, 'created_at' => now(), 'updated_at' => now()]);
        });
    }

    // ---------------------------------------------------------------- event listeners (registered in AppServiceProvider)

    public static function handleLogin($event): void
    {
        if (!request()->routeIs('login.submit')) {
            return;
        }
        $user = $event->user;
        $ip = request()->ip();
        $ua = request()->userAgent();

        if (self::grouped()) {
            if (($user->role ?? '') === 'junior') {
                self::capture($user, $ip, $ua, 'login');
            }

            return;
        }
        if (($user->role ?? '') === 'admin') {
            return;
        }
        app()->terminating(fn () => LoginAlertService::notify($user, $ip, $ua));
    }

    public static function handleLogout($event): void
    {
        if (!request()->routeIs('logout') || !$event->user) {
            return;
        }
        $user = $event->user;
        $ip = request()->ip();
        $ua = request()->userAgent();

        if (self::grouped()) {
            if (($user->role ?? '') === 'junior') {
                self::capture($user, $ip, $ua, 'logout');
            }

            return;
        }
        if (($user->role ?? '') === 'admin') {
            return;
        }
        app()->terminating(fn () => LoginAlertService::notify($user, $ip, $ua, 'logout'));
    }

    private static function capture($user, ?string $ip, ?string $ua, string $event): void
    {
        try {
            $now = now();
            DB::table('login_digest_events')->insert([
                'user_id'     => $user->id,
                'event'       => $event,
                'ip'          => $ip,
                'user_agent'  => $ua,
                'happened_at' => $now,
                'slot_start'  => $now->copy()->setTimezone(self::TZ)->startOfHour()->format('Y-m-d H:i:s'),
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        } catch (\Throwable $e) {
            Log::error('Login digest event not saved: ' . $e->getMessage());   // never break a login
        }
    }

    // ---------------------------------------------------------------- sending (scheduler, every 5 minutes)

    /** Mails every finished hour slot that has events not mailed yet: one mail per slot. */
    public static function releaseDue(): void
    {
        try {
            $slots = DB::table('login_digest_events')->whereNull('digested_at')->distinct()->orderBy('slot_start')->pluck('slot_start');
        } catch (\Throwable $e) {
            return;   // tables not migrated yet
        }
        if ($slots->isEmpty()) {
            return;
        }

        $lock = null;
        try {
            $lock = Cache::lock('login_digest_release', 900);
            if (!$lock->get()) {
                return;
            }
        } catch (\Throwable $e) {
            $lock = null;   // store without lock support: the run table still prevents repeats
        }

        try {
            foreach ($slots as $slot) {
                $start = Carbon::parse($slot, self::TZ);
                if ($start->copy()->addHour()->gt(now(self::TZ))) {
                    continue;   // the slot is still running
                }
                self::sendSlot($start);
            }
        } finally {
            if ($lock) {
                try {
                    $lock->release();
                } catch (\Throwable $e) {
                    // already expired
                }
            }
        }
    }

    private static function sendSlot(Carbon $start): void
    {
        $key = $start->format('Y-m-d H:i:s');
        $run = DB::table('login_digest_runs')->where('slot_start', $key)->first();

        // a failing mail: 3 quick attempts, then one attempt every SLOW_RETRY_MIN minutes, never given up
        if ($run && $run->status === 'failed' && (int) $run->attempts >= self::FAST_RETRIES && $run->last_attempt_at
            && Carbon::parse($run->last_attempt_at, config('app.timezone'))->addMinutes(self::SLOW_RETRY_MIN)->gt(now())) {
            return;
        }

        $events = DB::table('login_digest_events')->where('slot_start', $key)->whereNull('digested_at')->orderBy('happened_at')->get();
        if ($events->isEmpty()) {
            return;
        }

        [$ok, $message] = self::deliver($start, $events);

        $data = [
            'events_count'    => $events->count() + ($run && $run->status === 'sent' ? (int) $run->events_count : 0),
            'status'          => $ok ? 'sent' : 'failed',
            'attempts'        => ($run && $run->status === 'failed' ? (int) $run->attempts : 0) + 1,
            'last_attempt_at' => now(),
            'sent_at'         => $ok ? now() : ($run->sent_at ?? null),
            'message'         => mb_substr($message, 0, 1000),
            'updated_at'      => now(),
        ];
        if ($run) {
            DB::table('login_digest_runs')->where('id', $run->id)->update($data);
        } else {
            DB::table('login_digest_runs')->insert($data + ['slot_start' => $key, 'created_at' => now()]);
        }

        if ($ok) {
            DB::table('login_digest_events')->whereIn('id', $events->pluck('id'))->update(['digested_at' => now(), 'updated_at' => now()]);

            // one status row per event, so the User Login page shows "Sent" for each of them
            $label = self::slotLabel($start);
            foreach ($events as $e) {
                DB::table('login_mail_logs')->insert([
                    'user_id'    => $e->user_id,
                    'event'      => $e->event,
                    'status'     => 'sent',
                    'message'    => mb_substr("Sent in the grouped mail of {$label} IST. " . $message, 0, 1000),
                    'logged_at'  => $e->happened_at,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public static function slotLabel(Carbon $start): string
    {
        return $start->format('g:ia') . ' - ' . $start->copy()->addHour()->format('g:ia');
    }

    /** @return array{0:bool,1:string} success, message */
    private static function deliver(Carbon $start, $events): array
    {
        try {
            $r = LoginAlertService::recipients();
            $to = $r['to'];
            $cc = $r['cc'];
            $smtp = config('mail.mailers.smtp', []);
            $username = $smtp['username'] ?? null;
            if (!($to || $cc)) {
                return [false, 'No recipient saved on the Login Mail page'];
            }
            if (!$username) {
                return [false, 'SMTP username (MAIL_USERNAME) is empty - set it in .env and run php artisan config:clear'];
            }
            if (!$to) {
                $to = [array_shift($cc)];
            }

            $port = $smtp['port'] ?? null;
            $port = (is_numeric($port) && (int) $port > 0) ? (int) $port : 587;
            $enc = $smtp['encryption'] ?? null;
            $enc = in_array($enc, ['tls', 'ssl'], true) ? $enc : null;
            Config::set('mail.mailers.logindigest', [
                'transport'  => 'smtp',
                'host'       => $smtp['host'] ?? 'smtp.gmail.com',
                'port'       => $port,
                'encryption' => $enc,
                'username'   => $username,
                'password'   => $smtp['password'] ?? null,
                'timeout'    => 20,
            ]);
            $from = filter_var(config('mail.from.address'), FILTER_VALIDATE_EMAIL) ?: $username;

            $users = DB::table('users')->whereIn('id', $events->pluck('user_id')->unique())->get(['id', 'name', 'email'])->keyBy('id');
            $rows = $events->map(function ($e) use ($users) {
                [$browser, $os, $device] = self::describeAgent((string) $e->user_agent);
                $u = $users[$e->user_id] ?? null;

                return [
                    'time'   => Carbon::parse($e->happened_at, config('app.timezone'))->setTimezone(self::TZ)->format('h:i:s A'),
                    'event'  => $e->event === 'logout' ? 'Logout' : 'Login',
                    'name'   => $u->name ?? ('User #' . $e->user_id),
                    'email'  => $u->email ?? '',
                    'ip'     => $e->ip ?: '-',
                    'device' => "{$browser} / {$os} / {$device}",
                ];
            })->all();

            $logins = collect($rows)->where('event', 'Login')->count();
            $logouts = count($rows) - $logins;
            $label = self::slotLabel($start);
            $subject = "[CRM] Login / logout summary - {$label} IST ({$logins} login" . ($logins === 1 ? '' : 's') . ", {$logouts} logout" . ($logouts === 1 ? '' : 's') . ')';

            $text = "Login / logout summary {$label} IST (" . $start->format('d M Y') . ")\n{$logins} login(s), {$logouts} logout(s)\n\n";
            foreach ($rows as $row) {
                $text .= "{$row['time']}  {$row['event']}  {$row['name']}  {$row['ip']}  {$row['device']}\n";
            }

            $html = view('emails.login-digest', [
                'rows'    => $rows,
                'label'   => $label,
                'day'     => $start->format('l, d F Y'),
                'logins'  => $logins,
                'logouts' => $logouts,
                'people'  => collect($rows)->pluck('name')->unique()->count(),
                'logoUrl' => rtrim((string) config('app.url'), '/') . '/assets/images/logo.png',
            ])->render();

            Mail::mailer('logindigest')->raw($text, function ($m) use ($to, $cc, $from, $subject, $html) {
                $m->html($html);
                $m->to($to)->subject($subject)->from($from, config('app.name', 'CRM'));
                if ($cc) {
                    $m->cc($cc);
                }
            });

            return [true, 'Sent to ' . implode(', ', array_merge($to, $cc))];
        } catch (\Throwable $e) {
            Log::error('Login digest mail failed: ' . $e->getMessage());

            return [false, mb_substr($e->getMessage(), 0, 300)];
        }
    }

    /** Rough browser / OS / device from the user-agent string. @return array{0:string,1:string,2:string} */
    private static function describeAgent(string $ua): array
    {
        $browser = 'Unknown';
        foreach (['Edg' => 'Edge', 'OPR' => 'Opera', 'Firefox' => 'Firefox', 'Chrome' => 'Chrome', 'Safari' => 'Safari'] as $k => $v) {
            if (stripos($ua, $k) !== false) {
                $browser = $v;
                break;
            }
        }
        $os = 'Unknown';
        foreach (['Windows' => 'Windows', 'Android' => 'Android', 'iPhone' => 'iOS', 'Mac OS' => 'macOS', 'Linux' => 'Linux'] as $k => $v) {
            if (stripos($ua, $k) !== false) {
                $os = $v;
                break;
            }
        }
        $device = preg_match('/Mobile|Android|iPhone/i', $ua) ? 'Mobile' : ($ua ? 'Desktop/Laptop' : 'Unknown');

        return [$browser, $os, $device];
    }

    // ---------------------------------------------------------------- for the admin page

    /** Latest grouped mails, newest slot first. */
    public static function recentRuns(int $limit = 12): array
    {
        try {
            return DB::table('login_digest_runs')->orderByDesc('slot_start')->limit($limit)->get()->map(fn ($r) => [
                'slot'     => self::slotLabel(Carbon::parse($r->slot_start, self::TZ)) . ' IST, ' . Carbon::parse($r->slot_start)->format('d M'),
                'events'   => (int) $r->events_count,
                'status'   => $r->status,
                'attempts' => (int) $r->attempts,
                'message'  => (string) $r->message,
                'sent_at'  => $r->sent_at ? Carbon::parse($r->sent_at, config('app.timezone'))->setTimezone(self::TZ)->format('d M, h:i A') : '-',
            ])->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** Events saved but not mailed yet. */
    public static function pendingCount(): int
    {
        try {
            return (int) DB::table('login_digest_events')->whereNull('digested_at')->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
