<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTimerLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTimerPause;
use App\Models\TimerSetting;

class TimerController extends Controller
{

    public function index()
    {
        $timersetting = TimerSetting::first();
        return view('timers.admin', compact('timersetting'));
    }

    // Update Work Day Duration only
    public function updateWorkDay(Request $request)
    {
        $request->validate([
            'hours' => 'required|integer|min:0|max:24',
            'minutes' => 'required|integer|min:0|max:59',
        ]);

        $timersetting = TimerSetting::first() ?? new TimerSetting();

        $timersetting->work_day_seconds = ($request->hours * 3600) + ($request->minutes * 60);
        $timersetting->save();

        return redirect()->back()->with('success', 'Work Day Duration updated successfully!');
    }

    // Update Daily Base Time only
    public function updateBaseTime(Request $request)
    {
        $request->validate([
            'daily_base_time' => 'required|date_format:H:i',
        ]);

        $timersetting = TimerSetting::first() ?? new TimerSetting();
        $timersetting->daily_base_time = $request->daily_base_time;
        $timersetting->save();

        return redirect()->back()->with('success', 'Daily Base Time updated successfully!');
    }

    public function seniorTimers()
    {
        $timerSetting = TimerSetting::first();
        $workDaySeconds = $timerSetting ? $timerSetting->work_day_seconds : 9 * 60 * 60;

        $juniors = User::where('role', 'junior')->get();

        $timers = $juniors->map(function ($junior) use ($workDaySeconds) {
            $timer = UserTimerLog::where('user_id', $junior->id)->latest()->first();

            if ($timer) {
                $remaining_seconds = $timer->remaining_seconds;
                $elapsed_seconds = $workDaySeconds - $remaining_seconds;
                $status = $timer->status;
                $button_status = $timer->button_status;
                $notice_status = $timer->notice_status;
            } else {
                $remaining_seconds = $workDaySeconds;
                $elapsed_seconds = 0;
                $status = 'running';
                $button_status = 1;
                $notice_status = 0;
            }

            return [
                'user_id'          => $junior->id,
                'name'             => $junior->name,
                'email'            => $junior->email,
                'remaining_seconds' => $remaining_seconds,
                'elapsed_seconds'  => $elapsed_seconds,
                'status'           => $status,
                'button_status'    => $button_status,
                'notice_status'    => $notice_status,
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

        $timerSetting = TimerSetting::first();
        $workDaySeconds = $timerSetting ? $timerSetting->work_day_seconds : 9 * 60 * 60;

        $now = now();

        if ($timer->status === 'running') {
            $seconds_passed = $now->diffInSeconds($timer->updated_at);
            $timer->remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed); // subtract!
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

        $elapsed_seconds = $workDaySeconds - $timer->remaining_seconds;

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
        $timerSetting = TimerSetting::first();
        $workDaySeconds = $timerSetting ? $timerSetting->work_day_seconds : 9 * 60 * 60;

        $juniors = User::where('role', 'junior')->get();

        $timers = $juniors->map(function ($junior) use ($workDaySeconds) {
            $timer = UserTimerLog::where('user_id', $junior->id)->latest()->first();

            if ($timer && $timer->status === 'running') {
                $seconds_passed = now()->diffInSeconds($timer->updated_at);
                $remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
            } else {
                $remaining_seconds = $timer ? $timer->remaining_seconds : $workDaySeconds;
            }

            return [
                'user_id'          => $junior->id,
                'remaining_seconds' => $remaining_seconds,
                'elapsed_seconds'  => $workDaySeconds - $remaining_seconds,
                'status'           => $timer ? $timer->status : 'running',
                'pause_type'       => $timer ? $timer->pause_type : null,
                'logout'           => $remaining_seconds <= 0,
            ];
        });

        return response()->json($timers);
    }
}
