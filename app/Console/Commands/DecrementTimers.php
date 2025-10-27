<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class DecrementTimers extends Command
{
    protected $signature = 'timers:decrement';
    protected $description = 'Decrement running/resumed timers every 3 seconds within the current minute with 66 seconds total per minute';

    protected $lastRemaining = [];

    public function handle()
    {
        $this->info('⏳ Timer decrement process started...');

        $startTime = Carbon::now();
        $endTime = $startTime->copy()->addMinute()->startOfMinute();
        $interval = 3; // base cycle duration in seconds
        $nextTick = microtime(true);

        $cycleCounter = 0;
        $totalCyclesPerMinute = 60 / $interval; // 20 cycles
        $cyclesSubtract4 = 13; // 6 cycles subtract 4 seconds
        $cyclesSubtract3 = $totalCyclesPerMinute - $cyclesSubtract4; // 14 cycles subtract 3 seconds

        while (Carbon::now()->lessThan($endTime)) {
            try {
                $cycleStart = microtime(true);

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

                    // Determine decrement for this cycle
                    $decrement = ($cycleCounter < $cyclesSubtract4) ? 4 : 3;

                    if (!isset($this->lastRemaining[$userId]) || $this->lastRemaining[$userId] === $currentRemaining) {
                        DB::table('user_timer_logs')
                            ->where('id', $timer->id)
                            ->update([
                                'remaining_seconds' => max($currentRemaining - $decrement, 0),
                                'updated_at' => now(),
                            ]);

                        $affected++;
                        $this->lastRemaining[$userId] = $currentRemaining - $decrement;
                    } else {
                        $this->lastRemaining[$userId] = $currentRemaining;
                    }
                }

                if ($affected > 0) {
                    $this->line(now() . " → Updated $affected latest timers with decrement of " . (($cycleCounter < $cyclesSubtract4) ? 4 : 3) . " seconds.");
                }

                // ✅ Compensate for drift
                $cycleCounter++;
                $nextTick += $interval;
                $sleepTime = $nextTick - microtime(true);
                if ($sleepTime > 0) {
                    usleep((int)($sleepTime * 1_000_000));
                }

            } catch (Throwable $e) {
                $this->error("❌ Error: " . $e->getMessage());
                $cycleCounter++;
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
