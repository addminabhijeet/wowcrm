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
            'daily_base_time'  => $setting ? $setting->daily_base_time : '07:00:00',  // 8 PM default
        ];
    }
    const WORK_DAY_SECONDS = 9 * 60 * 60;

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
            $button_status     = $timer->button_status; // 0 = hide, 1 = show
        }

        return view('dashboard.junior', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status',
            'button_status'
        ));
    }

    public function getButtonStatus()
    {
        $user  = Auth::user();

        $button_status = UserTimerLog::where('user_id', $user->id)
            ->latest()
            ->value('button_status') ?? 0;

        return response()->json([
            'button_status' => $button_status
        ]);
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
        $user   = Auth::user();
        $action = $request->input('action');

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
        if (!$timer) {
            return response()->json(['error' => 'Timer not found'], 404);
        }

        // Current time in IST
        $istNow = now('Asia/Kolkata');

        // Today 6 AM IST
        $ist6am = $istNow->copy()->startOfDay()->addHours(6);

        // Timer's updated_at in IST
        $timerUpdatedIst = $timer->updated_at->copy()->timezone('Asia/Kolkata');

        // Convert times to comparable numeric format YYMMDDHHMMSS
        $istNowNum        = $istNow->format('ymdHis');
        $ist6amNum        = $ist6am->format('ymdHis');
        $timerUpdatedNum  = $timerUpdatedIst->format('ymdHis');

        // Reset timer if last update was before 6 AM today
        if ($timerUpdatedNum < $ist6amNum) {
            $timer->remaining_seconds = self::WORK_DAY_SECONDS;
            $timer->status = 'running';
            $timer->pause_type = 'reset';
            $timer->updated_at = $istNow;
            $timer->save();

            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'remaining_seconds' => $timer->remaining_seconds,
                'elapsed_seconds'   => 0,
                'event_time'        => $istNow,
            ]);

            return response()->json([
                'success'           => true,
                'remaining_seconds' => $timer->remaining_seconds,
                'elapsed_seconds'   => 0,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'notice_status'     => $timer->notice_status,
                'logout'            => false
            ]);
        }

        // Update remaining seconds if timer was running
        if ($timer->status === 'running') {
            $seconds_passed = now()->diffInSeconds($timer->updated_at);
            $timer->remaining_seconds = max(0, $timer->remaining_seconds + $seconds_passed);
        }

        // Handle actions
        if ($action === 'resume') {
            $timer->status = 'running';
            $timer->pause_type = 'resume';
        } elseif ($action !== 'tick') {
            $timer->status = 'paused';
            $timer->pause_type = $action;
        }

        $timer->updated_at = now();
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
                'event_time'        => now(),
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
