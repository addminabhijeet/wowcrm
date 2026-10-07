<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails the admin-chosen addresses when a non-admin user logs in.
 * Recipients are stored in the `login_alert_emails` table and cached, so a login
 * normally costs no extra query. SMTP comes from .env MAIL_*.
 */
class LoginAlertService
{
    private const CACHE_KEY = 'login_alert_recipients';

    /** ['to' => [...], 'cc' => [...]] */
    public static function recipients(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                $rows = DB::table('login_alert_emails')->orderBy('id')->get(['type', 'email']);

                return [
                    'to' => $rows->where('type', 'to')->pluck('email')->values()->all(),
                    'cc' => $rows->where('type', 'cc')->pluck('email')->values()->all(),
                ];
            });
        } catch (\Throwable $e) {
            Log::error('Login alert recipients could not be read (is the login_alert_emails table migrated?): ' . $e->getMessage());

            return ['to' => [], 'cc' => []];
        }
    }

    public static function emails(): array
    {
        return self::recipients()['to'];
    }

    public static function saveRecipients(array $to, array $cc): void
    {
        $to = array_values(array_unique($to));
        $cc = array_values(array_unique(array_diff($cc, $to)));

        try {
            DB::transaction(function () use ($to, $cc) {
                DB::table('login_alert_emails')->delete();
                $now = now();
                $rows = [];
                foreach ($to as $e) {
                    $rows[] = ['type' => 'to', 'email' => $e, 'created_at' => $now, 'updated_at' => $now];
                }
                foreach ($cc as $e) {
                    $rows[] = ['type' => 'cc', 'email' => $e, 'created_at' => $now, 'updated_at' => $now];
                }
                if ($rows) {
                    DB::table('login_alert_emails')->insert($rows);
                }
            });
            Cache::forget(self::CACHE_KEY);
        } catch (\Throwable $e) {
            Log::error('Login alert recipients DB save failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function save(array $emails): void
    {
        self::saveRecipients($emails, self::recipients()['cc']);
    }

    /** Rough browser / OS / device from the user-agent string. */
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
        foreach (['Windows' => 'Windows', 'Android' => 'Android', 'iPhone' => 'iOS', 'iPad' => 'iOS', 'Mac OS' => 'macOS', 'Linux' => 'Linux'] as $k => $v) {
            if (stripos($ua, $k) !== false) {
                $os = $v;
                break;
            }
        }

        $device = preg_match('/Mobile|Android|iPhone/i', $ua) ? 'Mobile' : ($ua ? 'Desktop/Laptop' : 'Unknown');

        return [$browser, $os, $device];
    }

    public static function notify($user, string $ip, ?string $userAgent, string $event = 'login'): void
    {
        try {
            $r = self::recipients();
            $to = $r['to'];
            $cc = $r['cc'];
            // Read via config() (not env()) so it also works when the config is cached on the server
            $smtp = config('mail.mailers.smtp', []);
            $username = $smtp['username'] ?? null;
            if (!($to || $cc) || !$username) {
                self::record($user, 'skipped', !$username ? 'SMTP username (MAIL_USERNAME) is empty - set it in .env and run php artisan config:clear' : 'No recipient saved on Login Mail page', $event);
                return;
            }
            // If no "To" is set, promote the first CC address so the mail still has a recipient.
            if (!$to) {
                $to = [array_shift($cc)];
            }

            $port = $smtp['port'] ?? null;
            $port = (is_numeric($port) && (int) $port > 0) ? (int) $port : 587;
            $enc  = $smtp['encryption'] ?? null;
            $enc  = in_array($enc, ['tls', 'ssl'], true) ? $enc : null;

            Config::set('mail.mailers.loginalert', [
                'transport' => 'smtp',
                'host'      => $smtp['host'] ?? 'smtp.gmail.com',
                'port'      => $port,
                'encryption'=> $enc,
                'username'  => $username,
                'password'  => $smtp['password'] ?? null,
                'timeout'   => 10,
            ]);

            $from = filter_var(config('mail.from.address'), FILTER_VALIDATE_EMAIL) ?: $username;
            [$browser, $os, $device] = self::describeAgent((string) $userAgent);

            $body = ($event === 'logout' ? 'User logout alert' : 'User login alert') . "\n\n"
                . "User ID: {$user->id}\n"
                . "Name: {$user->name}\n"
                . "Email: {$user->email}\n"
                . 'Time: ' . now('Asia/Kolkata')->format('d M, h:i:s A') . " (IST)\n"
                . "IP Address: {$ip}\n"
                . "Browser: {$browser}\n"
                . "Operating System: {$os}\n"
                . "Device Type: {$device}\n"
                . 'Full User Agent: ' . ($userAgent ?: '-') . "\n";

            // Styled HTML version (the plain text above stays as the fallback part)
            try {
                $kolkata = now('Asia/Kolkata');
                $html = view('emails.login-alert', [
                    'event'     => $event,
                    'user'      => $user,
                    'ip'        => $ip,
                    'browser'   => $browser,
                    'os'        => $os,
                    'device'    => $device,
                    'userAgent' => $userAgent,
                    'whenDate'  => $kolkata->format('l, d F'),
                    'whenTime'  => $kolkata->format('h:i:s A'),
                    'logoUrl'   => rtrim((string) config('app.url'), '/') . '/assets/images/logo.png',
                ])->render();
            } catch (\Throwable $viewError) {
                $html = null; // fall back to plain text
                Log::error('Login alert template failed: ' . $viewError->getMessage());
            }

            Mail::mailer('loginalert')->raw($body, function ($m) use ($to, $cc, $user, $from, $event, $html) {
                if ($html) {
                    $m->html($html);
                }
                $m->to($to)
                    ->subject("[CRM] {$user->name} " . ($event === 'logout' ? 'logged out' : 'logged in'))
                    ->from($from, config('app.name', 'CRM'));
                if ($cc) {
                    $m->cc($cc);
                }
            });
            self::record($user, 'sent', 'Sent to ' . implode(', ', array_merge($to, $cc)), $event);
        } catch (\Throwable $e) {
            // Never block or break a user's login because of mail trouble.
            Log::error('Login alert mail failed: ' . $e->getMessage());
            self::record($user, 'failed', mb_substr($e->getMessage(), 0, 300), $event);
        }
    }

    // ---- Send-status log: one row per login/logout mail in the `login_mail_logs` table ----

    private static function record($user, string $status, string $message, string $event = 'login'): void
    {
        try {
            DB::table('login_mail_logs')->insert([
                'user_id'    => $user->id,
                'event'      => $event,
                'status'     => $status,
                'message'    => mb_substr($message, 0, 1000),
                'logged_at'  => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Status logging must never affect login.
            Log::error('Login mail status could not be saved: ' . $e->getMessage());
        }
    }

    /** Status entries recorded on a given date (Y-m-d, app timezone). */
    public static function statusFor(string $date): array
    {
        try {
            return DB::table('login_mail_logs')
                ->whereBetween('logged_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
                ->orderBy('id')
                ->get(['user_id', 'event', 'status', 'message', 'logged_at'])
                ->map(fn ($r) => [
                    'user_id' => $r->user_id,
                    'time'    => $r->logged_at,
                    'status'  => $r->status,
                    'message' => $r->message,
                    'event'   => $r->event,
                ])
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
