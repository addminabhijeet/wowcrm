<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTimerLog;
use App\Models\UserTimerPause;
use App\Models\TimerSetting;

class TimerApiController extends Controller
{
    public function update(Request $request)
    {
        // Get user ID from request (since no auth)
        $userId = $request->input('user_id'); 
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $action = $request->input('action');

        // ---------------------------
        // Your existing updateTimer logic
        // ---------------------------

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
        if (!$timer) {
            return response()->json(['success' => false, 'message' => 'Timer not found'], 404);
        }

        $currentTime = now();
        $timerSetting = TimerSetting::first();
        $workDaySeconds = $timerSetting ? $timerSetting->work_day_seconds : 9 * 3600;

        if ($timer->status === 'running') {
            $secondsPassed = $currentTime->diffInSeconds($timer->updated_at);
            $timer->remaining_seconds = max(0, $timer->remaining_seconds + ($secondsPassed / 2));
        }

        // Logic for pause/resume/lunch/tea/tick
        // (Copy your existing logic here)

        $timer->updated_at = $currentTime;
        $timer->save();

        $elapsedSeconds = max(0, $workDaySeconds - $timer->remaining_seconds);

        return response()->json([
            'success' => true,
            'remaining_seconds' => $timer->remaining_seconds,
            'elapsed_seconds' => $elapsedSeconds,
            'status' => $timer->status,
            'pause_type' => $timer->pause_type,
            'notice_status' => $timer->notice_status,
            'logout' => $timer->remaining_seconds <= 0
        ]);
    }
}
