<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTimerLog;
use App\Models\User;

class TimerController extends Controller
{
    const WORK_DAY_SECONDS = 9 * 60 * 60;

    public function seniorTimers()
    {
        $juniors = User::where('role', 'junior')->get();

        $timers = $juniors->map(function($junior) {
            $timer = UserTimerLog::where('user_id', $junior->id)->latest()->first();

            if ($timer && $timer->status === 'running') {
                $seconds_passed = now()->diffInSeconds($timer->updated_at);
                $remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
            } else {
                $remaining_seconds = $timer ? $timer->remaining_seconds : self::WORK_DAY_SECONDS;
            }

            return [
                'user_id'          => $junior->id,
                'name'             => $junior->name,
                'email'            => $junior->email,
                'user'              => $junior,
                'remaining_seconds' => $remaining_seconds,
                'elapsed_seconds'   => self::WORK_DAY_SECONDS - $remaining_seconds,
                'status'            => $timer ? $timer->status : 'running',
            ];
        });

        return view('timers.senior', compact('timers'));
    }

    


    


    /**
     * Timers for Admin Dashboard (all juniors + all seniors)
     */
    public function adminTimers()
    {
        // Assuming roles 'junior' and 'senior'
        $userIds = User::whereIn('role', ['junior', 'senior'])->pluck('id');

        $timers = UserTimerLog::with('pauses', 'user')
            ->whereIn('user_id', $userIds)
            ->latest()
            ->get();

        return view('timers.admin', compact('timers'));
    }

    /**
     * Optional: show timer details for a specific user
     */
    public function juniorTimers()
    {
        // Assuming roles 'junior' and 'senior'
        $userIds = User::whereIn('role', 'junior')->pluck('id');

        $timers = UserTimerLog::with('pauses', 'user')
            ->whereIn('user_id', $userIds)
            ->latest()
            ->get();

        return view('timers.admin', compact('timers'));
    }
}
