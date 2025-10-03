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
        try {
            $settings = $this->getTimerSettings();
            $workDaySeconds = $settings['work_day_seconds'] ?? 32400; // fallback 9hrs
            $dailyBaseTime  = $settings['daily_base_time'] ?? '07:00:00';

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.'
                ], 401);
            }

            $action = $request->input('action', 'tick');

            // Get latest timer log
            $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
            if (!$timer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timer not found.'
                ], 404);
            }

            $istNow = now('Asia/Kolkata');

            // Ensure start_time and updated_at are Carbon instances
            $timer->start_time = $timer->start_time ? \Carbon\Carbon::parse($timer->start_time) : null;
            $timer->updated_at = $timer->updated_at ? \Carbon\Carbon::parse($timer->updated_at) : $istNow;

            [$h, $m, $s] = explode(':', $dailyBaseTime);
            $todayBaseTime = $istNow->copy()->startOfDay()
                ->addHours((int)$h)
                ->addMinutes((int)$m)
                ->addSeconds((int)$s);

            // Block before daily base time if timer hasn't started
            if ($istNow->lt($todayBaseTime) && !$timer->start_time) {
                return response()->json([
                    'success' => false,
                    'message' => "Timer can start only after {$dailyBaseTime} IST."
                ]);
            }

            // Initialize timer if needed
            if (!$timer->start_time) {
                $timer->start_time = $todayBaseTime;
                $timer->remaining_seconds = $workDaySeconds;
                $timer->status = 'running';
                $timer->pause_type = 'resume';
                $timer->save();
            }

            // Calculate elapsed since last update
            $secondsPassed = $istNow->diffInSeconds($timer->updated_at);

            if ($timer->status === 'running') {
                $timer->remaining_seconds = max(0, $timer->remaining_seconds - $secondsPassed);
                $timer->start_time = $timer->start_time->copy()->addSeconds($secondsPassed);
            }

            // --- Reset logic with weekend-aware handling ---
            $gap = $istNow->diffInSeconds($timer->start_time);
            $threshold = 3 * 3600; // 3 hours

            if ($gap > $threshold) {
                $newStart = $todayBaseTime;

                // If current time past end of today's workday, roll to next valid day
                if ($istNow->gt($todayBaseTime->copy()->addSeconds($workDaySeconds))) {
                    $newStart = $todayBaseTime->copy()->addDay();

                    // Skip weekends
                    while (in_array($newStart->dayOfWeek, [\Carbon\Carbon::SATURDAY, \Carbon\Carbon::SUNDAY])) {
                        $newStart->addDay();
                    }
                }

                $timer->start_time = $newStart;
                $timer->remaining_seconds = $workDaySeconds;
                $timer->status = 'running';
                $timer->pause_type = 'reset';
            }

            // Handle actions
            if ($action === 'resume') {
                $timer->status = 'running';
                $timer->pause_type = 'resume';
            } elseif ($action !== 'tick') {
                $timer->status = 'paused';
                $timer->pause_type = $action;
            }

            $timer->updated_at = $istNow;
            $timer->save();

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

            $elapsed_seconds = $workDaySeconds - $timer->remaining_seconds;

            return response()->json([
                'success'           => true,
                'remaining_seconds' => $timer->remaining_seconds,
                'elapsed_seconds'   => $elapsed_seconds,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'notice_status'     => $timer->notice_status ?? 0,
                'logout'            => $timer->remaining_seconds <= 0
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ], 500);
        }
    }
}
