<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails the admin-chosen addresses when a non-admin user logs in.
 * Recipients live in a small JSON file (no DB query on login); SMTP comes from .env MAIL_*.
 */
class LoginAlertService
{
    private static function file(): string
    {
        return storage_path('app/login_alert_emails.json');
    }

    /** ['to' => [...], 'cc' => [...]]; an older flat list counts as "to". */
    public static function recipients(): array
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
        file_put_contents(self::file(), json_encode([
            'to' => array_values(array_unique($to)),
            'cc' => array_values(array_unique($cc)),
        ]), LOCK_EX);
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
        } catch (\Throwable $e) {
            // Never block or break a user's login because of mail trouble.
            Log::error('Login alert mail failed: ' . $e->getMessage());
        }
    }
}
