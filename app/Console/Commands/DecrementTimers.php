<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class DecrementTimers extends Command
{
    protected $signature = 'timers:decrement';
    protected $description = 'Decrement running/resumed timers every 3 seconds within the current minute';

    protected $lastRemaining = [];

    public function handle()
    {
        $this->info('⏳ Timer decrement process started...');

        $startTime = Carbon::now();
        $endTime = $startTime->copy()->addMinute()->startOfMinute();
        $interval = 3; // intended cycle duration in seconds
        $nextTick = microtime(true);

        while (Carbon::now()->lessThan($endTime)) {
            try {
                $cycleStart = microtime(true);

                // --- original logic unchanged ---
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

                    if (!isset($this->lastRemaining[$userId]) || $this->lastRemaining[$userId] === $currentRemaining) {
                        DB::table('user_timer_logs')
                            ->where('id', $timer->id)
                            ->update([
                                'remaining_seconds' => max($currentRemaining - 3, 0),
                                'updated_at' => now(),
                            ]);

                        $affected++;
                        $this->lastRemaining[$userId] = $currentRemaining - 3;
                    } else {
                        $this->lastRemaining[$userId] = $currentRemaining;
                    }
                }

                if ($affected > 0) {
                    $this->line(now() . " → Updated $affected latest timers.");
                }

                // ✅ Compensate for drift
                $nextTick += $interval;
                $sleepTime = $nextTick - microtime(true);
                if ($sleepTime > 0) {
                    usleep((int)($sleepTime * 1_000_000)); // microseconds precision
                }

            } catch (Throwable $e) {
                $this->error("❌ Error: " . $e->getMessage());
                // even on error, keep nextTick aligned
                $nextTick += $interval;
                $sleepTime = $nextTick - microtime(true);
                if ($sleepTime > 0) {
                    usleep((int)($sleepTime * 1_000_000));
                }
            }
        }

        $this->info("⏹ Timer decrement process completed for this minute.");
    }
}

