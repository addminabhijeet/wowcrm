<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// C&M group report mail: checked every 5 minutes, sends once in each admin-chosen Kolkata hour
\Illuminate\Support\Facades\Schedule::command('report:send-group-mail')->everyFiveMinutes()->withoutOverlapping();

// Added: send only the slot that just ended (at the admin-chosen hour) instead of the full-day report.
// The full-report schedule above is kept untouched; it is only skipped so the two mails are not both sent.
foreach (\Illuminate\Support\Facades\Schedule::events() as $__event) {
    if (str_contains((string) $__event->command, 'report:send-group-mail')) {
        $__event->skip(fn () => true);
    }
}
// Added: php artisan report:test-mail you@example.com [--slot=8] [--dry]  -> sends BOTH templates (slot + full) to that address only.
Artisan::command('report:test-mail {to : Test recipient (saved recipients are NOT used)} {--slot=8 : Slot index 0-8 (8 = 4:00am - 5:00am)} {--dry : Render only, send nothing}', function (string $to) {
    $slot = (int) $this->option('slot');
    if ($slot < 0 || $slot > 8 || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $this->error('Give a valid email and --slot between 0 and 8.');
        return 1;
    }
    foreach (\App\Services\GroupCallReportMail::sendTest($to, $slot, (bool) $this->option('dry')) as $line) {
        $this->line($line);
    }
})->purpose('Send a test of the slot and full C&M report templates');
\Illuminate\Support\Facades\Schedule::call(function () {
    \App\Services\GroupCallReportMail::sendDueSlotMails();
})->name('report-send-slot-mail')->everyFiveMinutes()->withoutOverlapping();

// Grouped login/logout mails: one mail per finished hour slot that had junior logins or logouts
\Illuminate\Support\Facades\Schedule::call(function () {
    if (class_exists(\App\Services\LoginDigestMail::class)) {
        \App\Services\LoginDigestMail::releaseDue();
    }
})->name('login-digest-mail')->everyFiveMinutes()->withoutOverlapping();

// Navbar call duration of the juniors: rebuild the per-extension numbers of the current / previous shift when a sheet was uploaded since
\Illuminate\Support\Facades\Schedule::call(function () {
    if (class_exists(\App\Services\CallDurationSummary::class)) {
        \App\Services\CallDurationSummary::refreshStale();
    }
})->name('call-duration-summary')->everyTenMinutes()->withoutOverlapping();
