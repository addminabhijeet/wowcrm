<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTimerLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTimerPause;

class TimerController extends Controller
{
    const WORK_DAY_SECONDS = 9 * 60 * 60;

    public function seniorTimers()
    {
        $juniors = User::where('role', 'junior')->get();

        $timers = $juniors->map(function ($junior) {
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
                'user'             => $junior,
                'remaining_seconds' => $remaining_seconds,
                'elapsed_seconds'  => self::WORK_DAY_SECONDS - $remaining_seconds,
                'status'           => $timer ? $timer->status : 'running',
                'button_status'    => $timer ? $timer->button_status : 0,
            ];
        });

        return view('timers.senior', compact('timers'));
    }


    public function toggleButtonStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'action'  => 'required|string|in:enable,disable',
        ]);

        $userId = $request->user_id;
        $action = $request->action;

        $timerLog = UserTimerLog::where('user_id', $userId)->latest()->first();
        if (!$timerLog) return response()->json(['success' => false, 'message' => 'Timer not found']);

        $timerLog->button_status = $action == 'enable' ? 1 : 0;
        $timerLog->save();

        return response()->json([
            'success' => true,
            'button_status' => $timerLog->button_status
        ]);
    }

    public function toggleAllStatus(Request $request)
    {
        $request->validate(['action' => 'required|string|in:enable,disable']);

        $status = $request->action == 'enable' ? 1 : 0;

        
        $juniors = User::where('role', 'junior')->get();
        $updated = [];

        foreach ($juniors as $junior) {
            $timerLog = UserTimerLog::where('user_id', $junior->id)->latest()->first();
            if ($timerLog) {
                $timerLog->button_status = $status;
                $timerLog->save();

                $updated[] = [
                    'user_id' => $junior->id,
                    'button_status' => $timerLog->button_status
                ];
            }
        }

        return response()->json(['success' => true, 'updated' => $updated]);
    }



    public function updateTimer(Request $request)
    {
        $user = Auth::user();
        $action = $request->input('action');

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
        if (!$timer) return response()->json(['error' => 'Timer not found'], 404);

        $now = now();

        if ($timer->status === 'running') {
            $seconds_passed = $now->diffInSeconds($timer->updated_at);
            $timer->remaining_seconds = max(0, $timer->remaining_seconds + $seconds_passed);
        }

        if ($action === 'resume') {
            $timer->status = 'running';
            $timer->pause_type = 'resume';
        } elseif ($action !== 'tick') {
            $timer->status = 'paused';
            $timer->pause_type = $action; 
        }

        $timer->updated_at = $now;
        $timer->save();

        $elapsed_seconds = self::WORK_DAY_SECONDS - $timer->remaining_seconds;

     
        if ($action !== 'tick') {
            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'remaining_seconds' => $timer->remaining_seconds,
                'elapsed_seconds'   => $elapsed_seconds,
                'event_time'        => $now,
            ]);
        }

        return response()->json([
            'remaining_seconds' => $timer->remaining_seconds,
            'elapsed_seconds'   => $elapsed_seconds,
            'status'            => $timer->status,
            'pause_type'        => $timer->pause_type,
            'logout'            => $timer->remaining_seconds <= 0
        ]);
    }

    public function allJuniorTimers()
    {
        $juniors = User::where('role', 'junior')->get();

        $timers = $juniors->map(function ($junior) {
            $timer = UserTimerLog::where('user_id', $junior->id)->latest()->first();

            if ($timer && $timer->status === 'running') {
                $seconds_passed = now()->diffInSeconds($timer->updated_at);
                $remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
            } else {
                $remaining_seconds = $timer ? $timer->remaining_seconds : self::WORK_DAY_SECONDS;
            }

            return [
                'user_id'          => $junior->id,
                'remaining_seconds' => $remaining_seconds,
                'elapsed_seconds'  => self::WORK_DAY_SECONDS - $remaining_seconds,
                'status'           => $timer ? $timer->status : 'running',
                'pause_type'       => $timer ? $timer->pause_type : null,
                'logout'           => $remaining_seconds <= 0,
            ];
        });

        return response()->json($timers);
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
