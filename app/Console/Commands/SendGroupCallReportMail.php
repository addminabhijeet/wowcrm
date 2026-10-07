<?php

namespace App\Console\Commands;

use App\Services\GroupCallReportMail;
use Illuminate\Console\Command;

class SendGroupCallReportMail extends Command
{
    protected $signature = 'report:send-group-mail';

    protected $description = 'Email the C&M group report when the current Kolkata hour is one of the hours chosen in admin (runs every 5 minutes, sends once per hour)';

    public function handle(): int
    {
        $slot = GroupCallReportMail::dueSlot();
        if (!$slot) {
            return self::SUCCESS;
        }

        $result = GroupCallReportMail::send('schedule', $slot['date'], $slot['hour']);
        $this->info("{$slot['date']} hour {$slot['hour']}: {$result['status']} - {$result['message']}");

        return self::SUCCESS;
    }
}
