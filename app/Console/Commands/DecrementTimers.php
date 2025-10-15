<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class DecrementTimers extends Command
{
    protected $signature = 'timers:decrement';
    protected $description = 'Decrement running/resumed timers every 3 seconds';

    public function handle()
    {
        $this->info('⏳ Timer decrement process started...');

        while (true) {
            try {
                $affected = DB::table('user_timer_logs')
                    ->where('status', 'running')
                    ->where('pause_type', 'resume')
                    ->where(function ($query) {
                        $query->whereNull('last_decrement')
                              ->orWhereRaw('TIMESTAMPDIFF(SECOND, last_decrement, NOW()) >= 3');
                    })
                    ->update([
                        'remaining_seconds' => DB::raw('GREATEST(remaining_seconds - 3, 0)'),
                        'last_decrement'   => now(),
                        'updated_at'       => now(),
                    ]);

                if ($affected > 0) {
                    $this->line(now() . " → Updated $affected timers.");
                }

                sleep(3); // wait before next cycle
            } catch (Throwable $e) {
                $this->error("❌ Error: " . $e->getMessage());
                sleep(5); // short delay before retrying
            }
        }
    }
}
