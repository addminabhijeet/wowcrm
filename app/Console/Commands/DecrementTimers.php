<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class DecrementTimers extends Command
{
    protected $signature = 'timers:decrement';
    protected $description = 'Decrement active timers 17 times per minute, 4 seconds per cycle';

    /**
     * Tracks last known remaining seconds per user
     */
    protected array $lastRemaining = [];

    public function handle(): void
    {
        $this->info('⏳ Timer decrement process started...');

        $startTime = Carbon::now();
        $endTime = $startTime->copy()->addMinute()->startOfMinute();
        $totalCycles = 17;                     // Number of cycles per minute
        $decrementPerCycle = 4;                // Seconds to subtract each cycle
        $interval = 60 / $totalCycles;         // Spread evenly across 1 minute (~3.529s)
        $cycleCounter = 0;
        $nextTick = microtime(true);

        while (Carbon::now()->lessThan($endTime) && $cycleCounter < $totalCycles) {
            try {
                $cycleCounter++;
                $cycleStart = microtime(true);

                // 🔍 Fetch latest running timers per user
                $latestTimers = DB::table('user_timer_logs')
                    ->select('id', 'user_id', 'remaining_seconds')
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
                    $currentRemaining = (int) $timer->remaining_seconds;

                    // ✅ Only decrement if it hasn't been updated externally
                    if (!isset($this->lastRemaining[$userId]) || $this->lastRemaining[$userId] === $currentRemaining) {
                        $newRemaining = max($currentRemaining - $decrementPerCycle, 0);

                        DB::table('user_timer_logs')
                            ->where('id', $timer->id)
                            ->update([
                                'remaining_seconds' => $newRemaining,
                                'updated_at' => now(),
                            ]);

                        $this->lastRemaining[$userId] = $newRemaining;
                        $affected++;
                    } else {
                        // External update detected — sync the current value
                        $this->lastRemaining[$userId] = $currentRemaining;
                    }
                }

                if ($affected > 0) {
                    $this->line(sprintf(
                        '%s → ✅ Updated %d timer(s). (-%ds each) [Cycle %d/%d]',
                        now()->format('H:i:s'),
                        $affected,
                        $decrementPerCycle,
                        $cycleCounter,
                        $totalCycles
                    ));
                }

                // 🕒 Drift-compensated wait until next cycle
                $nextTick += $interval;
                $sleepTime = $nextTick - microtime(true);
                if ($sleepTime > 0) {
                    usleep((int) ($sleepTime * 1_000_000));
                }

            } catch (Throwable $e) {
                $this->error(sprintf(
                    '❌ Error on cycle %d: %s',
                    $cycleCounter,
                    $e->getMessage()
                ));
                // Continue timing despite error
                $nextTick += $interval;
                $sleepTime = $nextTick - microtime(true);
                if ($sleepTime > 0) {
                    usleep((int) ($sleepTime * 1_000_000));
                }
            }
        }

        // ⏸ Ensure 1 full minute total before exit
        $remainingTime = $endTime->floatDiffInSeconds(Carbon::now());
        if ($remainingTime > 0) {
            usleep((int) ($remainingTime * 1_000_000));
        }

        $this->info('⏹ Timer decrement process completed for this minute (' . $cycleCounter . ' cycles).');
    }
}
