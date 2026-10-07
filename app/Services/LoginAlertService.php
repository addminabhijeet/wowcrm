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

    private static function file(): string
    {
        return storage_path('app/login_alert_emails.json');
    }

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
            return self::recipientsFromFile(); // table not migrated yet
        }
    }

    private static function recipientsFromFile(): array
    {
        $empty = ['to' => [], 'cc' => []];
        $path = self::file();
        if (!is_file($path)) {
            return $empty;
        }
        $data = json_decode((string) file_get_contents($path), true);
        if (!is_array($data)) {
            return $empty;
        }
        if (array_is_list($data)) {
            return ['to' => $data, 'cc' => []];
        }

        return [
            'to' => array_values($data['to'] ?? []),
            'cc' => array_values($data['cc'] ?? []),
        ];
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
            // Table not migrated yet: keep working with the file.
            Log::error('Login alert recipients DB save failed: ' . $e->getMessage());
            file_put_contents(self::file(), json_encode(['to' => $to, 'cc' => $cc]), LOCK_EX);
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

    public static function notify($user, string $ip, ?string $userAgent): void
    {
        try {
            $r = self::recipients();
            $to = $r['to'];
            $cc = $r['cc'];
            $username = env('MAIL_USERNAME');
            if (!($to || $cc) || !$username) {
                self::record($user, 'skipped', !$username ? 'SMTP username not set in .env' : 'No recipient saved on Login Mail page');
                return;
            }
            // If no "To" is set, promote the first CC address so the mail still has a recipient.
            if (!$to) {
                $to = [array_shift($cc)];
            }

            $port = env('MAIL_PORT');
            $port = (is_numeric($port) && (int) $port > 0) ? (int) $port : 587;
            $enc  = env('MAIL_ENCRYPTION');
            $enc  = in_array($enc, ['tls', 'ssl'], true) ? $enc : null;

            Config::set('mail.mailers.loginalert', [
                'transport' => 'smtp',
                'host'      => env('MAIL_HOST', 'smtp.gmail.com'),
                'port'      => $port,
                'encryption'=> $enc,
                'username'  => $username,
                'password'  => env('MAIL_PASSWORD'),
                'timeout'   => 10,
            ]);

            $from = filter_var(env('MAIL_FROM_ADDRESS'), FILTER_VALIDATE_EMAIL) ?: $username;
            [$browser, $os, $device] = self::describeAgent((string) $userAgent);

            $body = "User login alert\n\n"
                . "User ID: {$user->id}\n"
                . "Name: {$user->name}\n"
                . "Email: {$user->email}\n"
                . "Role: {$user->role}\n"
                . 'Time: ' . now()->format('d M Y, h:i:s A (T)') . "\n"
                . "IP Address: {$ip}\n"
                . "Browser: {$browser}\n"
                . "Operating System: {$os}\n"
                . "Device Type: {$device}\n"
                . 'Full User Agent: ' . ($userAgent ?: '-') . "\n";

            Mail::mailer('loginalert')->raw($body, function ($m) use ($to, $cc, $user, $from) {
                $m->to($to)
                    ->subject("[CRM] {$user->name} logged in")
                    ->from($from, config('app.name', 'CRM'));
                if ($cc) {
                    $m->cc($cc);
                }
            });
            self::record($user, 'sent', 'Sent to ' . implode(', ', array_merge($to, $cc)));
        } catch (\Throwable $e) {
            // Never block or break a user's login because of mail trouble.
            Log::error('Login alert mail failed: ' . $e->getMessage());
            self::record($user, 'failed', mb_substr($e->getMessage(), 0, 300));
        }
    }

    // ---- Send-status log: one line per login in a per-day file (no DB queries on login) ----

    private static function logFile(string $date): string
    {
        return storage_path('app/login_mail_log/' . $date . '.jsonl');
    }

    private static function record($user, string $status, string $message): void
    {
        try {
            $file = self::logFile(now()->toDateString());
            if (!is_dir(dirname($file))) {
                @mkdir(dirname($file), 0775, true);
            }
            file_put_contents($file, json_encode([
                'user_id' => $user->id,
                'time'    => now()->toDateTimeString(),
                'status'  => $status,
                'message' => $message,
            ]) . "\n", FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            // Status logging must never affect login.
        }
    }

    /** Status entries recorded on a given date (Y-m-d). */
    public static function statusFor(string $date): array
    {
        $file = self::logFile($date);
        if (!is_file($file)) {
            return [];
        }
        $rows = [];
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $row = json_decode($line, true);
            if (is_array($row)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}
