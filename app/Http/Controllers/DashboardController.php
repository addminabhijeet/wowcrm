<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\UserTimerLog;
use App\Models\UserTimerPause;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\TimerSetting;

class DashboardController extends Controller
{
    /**
     * Get Timer Settings (with fallback defaults if DB is empty)
     */
    private function getTimerSettings()
    {
        $setting = TimerSetting::first();
        return [
            'work_day_seconds' => $setting ? $setting->work_day_seconds : (9 * 3600), // 9 hours default
            'daily_base_time'  => $setting ? $setting->daily_base_time : '20:00:00',  // 8 PM default
        ];
    }

    public function index()
    {
        $users = User::all();
        return view('dashboard.admin', compact('users'));
    }

    public function junior()
    {
        $settings = $this->getTimerSettings();
        $workDaySeconds = $settings['work_day_seconds'];

        $user  = Auth::user();
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $remaining_seconds = $workDaySeconds;
        $elapsed_seconds   = 0;
        $status            = 'running';

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds   = $workDaySeconds - $remaining_seconds;
            $status            = $timer->status;
        }

        return view('dashboard.junior', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }

    public function senior()
    {
        $settings = $this->getTimerSettings();
        $workDaySeconds = $settings['work_day_seconds'];

        $user  = Auth::user();
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $remaining_seconds = $workDaySeconds;
        $elapsed_seconds   = 0;
        $status            = 'running';

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds   = $workDaySeconds - $remaining_seconds;
            $status            = $timer->status;
        }

        return view('dashboard.senior', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }

    public function trainer()
    {
        $settings = $this->getTimerSettings();
        $workDaySeconds = $settings['work_day_seconds'];

        $user  = Auth::user();
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $remaining_seconds = $workDaySeconds;
        $elapsed_seconds   = 0;
        $status            = 'running';

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds   = $workDaySeconds - $remaining_seconds;
            $status            = $timer->status;
        }

        return view('dashboard.trainer', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }

    public function accountant()
    {
        $settings = $this->getTimerSettings();
        $workDaySeconds = $settings['work_day_seconds'];

        $user  = Auth::user();
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $remaining_seconds = $workDaySeconds;
        $elapsed_seconds   = 0;
        $status            = 'running';

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds   = $workDaySeconds - $remaining_seconds;
            $status            = $timer->status;
        }

        return view('dashboard.accountant', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }

    public function customer()
    {
        $user     = Auth::user();
        $payments = Payment::where('customer_id', $user->id)->get();

        return view('dashboard.customer', compact('payments'));
    }

    public function updateTimer(Request $request)
    {
        $settings = $this->getTimerSettings();
        $workDaySeconds = $settings['work_day_seconds'];
        $dailyBaseTime  = $settings['daily_base_time']; // e.g. "20:00:00"

        $user   = Auth::user();
        $action = $request->input('action');

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
        if (!$timer) {
            return response()->json(['error' => 'Timer not found'], 404);
        }

        $istNow = now('Asia/Kolkata');

        // Calculate today’s base time (e.g., today at 20:00 IST)
        [$h, $m, $s] = explode(':', $dailyBaseTime);
        $todayBaseTime = $istNow->copy()->startOfDay()->addHours($h)->addMinutes($m)->addSeconds($s);

        // If before today's base time and no start_time set, block timer
        if ($istNow->lt($todayBaseTime) && !$timer->start_time) {
            return response()->json([
                'success' => false,
                'message' => "Timer can start only after {$dailyBaseTime} IST."
            ]);
        }

        // Initialize if no start_time
        if (!$timer->start_time) {
            $timer->start_time = $todayBaseTime;
            $timer->remaining_seconds = $workDaySeconds;
            $timer->status = 'running';
            $timer->pause_type = 'resume';
            $timer->save();
        }

        // Update elapsed / remaining time
        $secondsPassed = $istNow->diffInSeconds($timer->updated_at);
        if ($timer->status === 'running') {
            $timer->remaining_seconds = max(0, $timer->remaining_seconds - $secondsPassed);
            $timer->start_time = $timer->start_time->copy()->addSeconds($secondsPassed);
        }

        // Reset threshold check (always 3 hrs)
        $gap = $istNow->diffInSeconds($timer->start_time);
        $threshold = 3 * 3600;

        if ($gap > $threshold) {
            if ($istNow->isFriday()) {
                $timer->start_time = $timer->start_time->copy()->addHours(72);
            } else {
                $timer->start_time = $timer->start_time->copy()->addHours(24);
            }
            $timer->remaining_seconds = $workDaySeconds;
            $timer->status = 'running';
            $timer->pause_type = 'reset';
        }

        // Handle action
        if ($action === 'resume') {
            $timer->status = 'running';
            $timer->pause_type = 'resume';
        } elseif ($action !== 'tick') {
            $timer->status = 'paused';
            $timer->pause_type = $action;
        }

        $timer->updated_at = $istNow;
        $timer->save();

        $elapsed_seconds = $workDaySeconds - $timer->remaining_seconds;

        // Record pause events
        if ($action !== 'tick') {
            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'remaining_seconds' => $timer->remaining_seconds,
                'event_time'        => $istNow,
            ]);
        }

        return response()->json([
            'success'           => true,
            'remaining_seconds' => $timer->remaining_seconds,
            'elapsed_seconds'   => $elapsed_seconds,
            'status'            => $timer->status,
            'pause_type'        => $timer->pause_type,
            'notice_status'     => $timer->notice_status,
            'logout'            => $timer->remaining_seconds <= 0
        ]);
    }
}
