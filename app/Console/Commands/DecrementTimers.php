<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class DecrementTimers extends Command
{
    protected $signature = 'timers:decrement';
    protected $description = 'Decrement running/resumed timers every 3 seconds based on real elapsed time';

    protected $lastRemaining = [];
    protected $lastUpdateTime = [];

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

                // ✅ Fetch only latest active timers
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
                    $lastUpdate = $this->lastUpdateTime[$userId] ?? $cycleStart;

                    // ✅ Compute real elapsed time since last update
                    $elapsed = $cycleStart - $lastUpdate;

                    // expected decrement proportional to real time
                    $decrement = floor($elapsed);

                    // Decrement only if user’s remaining time didn’t already change externally
                    if (!isset($this->lastRemaining[$userId]) || $this->lastRemaining[$userId] === $currentRemaining) {
                        if ($decrement > 0) {
                            DB::table('user_timer_logs')
                                ->where('id', $timer->id)
                                ->update([
                                    'remaining_seconds' => max($currentRemaining - $decrement, 0),
                                    'updated_at' => now(),
                                ]);

                            $affected++;
                            $this->lastRemaining[$userId] = $currentRemaining - $decrement;
                            $this->lastUpdateTime[$userId] = $cycleStart;
                        }
                    } else {
                        // external change (pause, resume, etc.)
                        $this->lastRemaining[$userId] = $currentRemaining;
                        $this->lastUpdateTime[$userId] = $cycleStart;
                    }
                }

                if ($affected > 0) {
                    $this->line(now() . " → Updated $affected latest timers.");
                }

                // ✅ Drift-compensated sleep
                $nextTick += $interval;
                $sleepTime = $nextTick - microtime(true);
                if ($sleepTime > 0) {
                    usleep((int)($sleepTime * 1_000_000));
                }

            } catch (Throwable $e) {
                $this->error("❌ Error: " . $e->getMessage());
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
