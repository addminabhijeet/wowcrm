<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class DecrementTimers extends Command
{
    protected $signature = 'timers:decrement';
    protected $description = 'Decrement running/resumed timers every 5 seconds within the current minute';

    // Array to store last seen remaining_seconds per user
    protected $lastRemaining = [];

    public function handle()
    {
        $this->info('⏳ Timer decrement process started...');

        // Get the start time of this cron run
        $startTime = Carbon::now();
        // Calculate the end time of the current minute
        $endTime = $startTime->copy()->addMinute()->startOfMinute();

        while (Carbon::now()->lessThan($endTime)) {
            try {
                // Get latest timers for all users where status=running and pause_type=resume
                $latestTimers = DB::table('user_timer_logs')
                    ->whereIn('id', function ($sub) {
                        $sub->select(DB::raw('MAX(id)'))
                            ->from('user_timer_logs')
                            ->where('status', 'running')
                            ->groupBy('user_id');
                    })
                    ->where('pause_type', 'resume')
                    ->get();

                $affected = 0;

                foreach ($latestTimers as $timer) {
                    $userId = $timer->user_id;
                    $currentRemaining = $timer->remaining_seconds;

                    // Check if remaining_seconds has changed since last cycle
                    if (!isset($this->lastRemaining[$userId]) || $this->lastRemaining[$userId] === $currentRemaining) {
                        // ✅ Decrement by 5
                        DB::table('user_timer_logs')
                            ->where('id', $timer->id)
                            ->update([
                                'remaining_seconds' => max($currentRemaining - 5, 0),
                                'updated_at' => now(),
                            ]);

                        $affected++;
                        // Update in-memory value
                        $this->lastRemaining[$userId] = $currentRemaining - 5;
                    } else {
                        // Update in-memory value to current for next comparison
                        $this->lastRemaining[$userId] = $currentRemaining;
                    }
                }

                if ($affected > 0) {
                    $this->line(now() . " → Updated $affected latest timers.");
                }

                // Wait 5 seconds before next cycle
                sleep(5);

            } catch (Throwable $e) {
                $this->error("❌ Error: " . $e->getMessage());
                sleep(5); // retry after short delay
            }
        }

        $this->info("⏹ Timer decrement process completed for this minute.");
    }
}
