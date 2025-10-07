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
        // Fetch timer settings
        $settings = TimerSetting::first();
        if (!$settings) {
            return response()->json(['error' => 'Timer settings not configured'], 500);
        }
        $workDaySeconds = $settings->work_day_seconds;

        $user  = Auth::user();
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $remaining_seconds = $workDaySeconds;
        $elapsed_seconds   = 0;
        $status            = 'running';
        $button_status     = 1; // default to show if no timer exists

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds   = $workDaySeconds - $remaining_seconds;
            $status            = $timer->status;
            $button_status     = $timer->button_status ?? 1;
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

    public function startTimer(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            $settings = TimerSetting::first();
            if (!$settings) {
                return response()->json(['error' => 'Timer settings missing'], 500);
            }

            $workDaySeconds = $settings->work_day_seconds;
            $today = now()->startOfDay();

            // Check if timer already exists for today
            $existingTimer = UserTimerLog::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->first();

            // Handle "check only" requests
            if ($request->input('check')) {
                return response()->json([
                    'exists' => $existingTimer ? true : false
                ]);
            }

            // If timer exists, respond accordingly
            if ($existingTimer) {
                return response()->json([
                    'exists' => true,
                    'timer' => $existingTimer
                ]);
            }

            // Create a new timer
            $timer = UserTimerLog::create([
                'user_id'           => $user->id,
                'login_id'          => $user->id,
                'start_time'        => now(),
                'remaining_seconds' => $workDaySeconds,
                'status'            => 'running',
            ]);

            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => 'running',
                'pause_type'        => 'start',
                'remaining_seconds' => $workDaySeconds,
                'event_time'        => now(),
            ]);

            return response()->json([
                'success' => true,
                'timer' => $timer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }



    public function senior()
    {
        // Fetch timer settings
        $settings = TimerSetting::first();
        if (!$settings) {
            return response()->json(['error' => 'Timer settings not configured'], 500);
        }
        $workDaySeconds = $settings->work_day_seconds;

        $user  = Auth::user();
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $remaining_seconds = $workDaySeconds;
        $elapsed_seconds   = 0;
        $status            = 'running';
        $button_status     = 1;

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds   = $workDaySeconds - $remaining_seconds;
            $status            = $timer->status;
            $button_status     = $timer->button_status ?? 1;
        }

        return view('dashboard.senior', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status',
            'button_status'
        ));
    }

    public function trainer()
    {
        // Fetch timer settings dynamically
        $settings = TimerSetting::first();
        if (!$settings) {
            return response()->json(['error' => 'Timer settings not configured'], 500);
        }
        $workDaySeconds = $settings->work_day_seconds;

        $user  = Auth::user();
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $remaining_seconds = $workDaySeconds;
        $elapsed_seconds   = 0;
        $status            = 'running';
        $button_status     = 1; // default to show if no timer exists

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds   = $workDaySeconds - $remaining_seconds;
            $status            = $timer->status;
            $button_status     = $timer->button_status ?? 1;
        }

        return view('dashboard.trainer', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status',
            'button_status'
        ));
    }

    public function accountant()
    {
        // Fetch timer settings dynamically
        $settings = TimerSetting::first();
        if (!$settings) {
            return response()->json(['error' => 'Timer settings not configured'], 500);
        }
        $workDaySeconds = $settings->work_day_seconds;

        $user  = Auth::user();
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $remaining_seconds = $workDaySeconds;
        $elapsed_seconds   = 0;
        $status            = 'running';
        $button_status     = 1; // default to show if no timer exists

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds   = $workDaySeconds - $remaining_seconds;
            $status            = $timer->status;
            $button_status     = $timer->button_status ?? 1;
        }

        return view('dashboard.accountant', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status',
            'button_status'
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

        // Get the latest timer log for the user
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
        if (!$timer) {
            return response()->json(['error' => 'Timer not found'], 404);
        }

        // Current time (uses default timezone)
        $currentTime = now();

        // Fetch work day duration from settings
        $timerSetting = TimerSetting::first();
        if (!$timerSetting) {
            return response()->json(['error' => 'Timer settings not configured'], 500);
        }

        $workDaySeconds = $timerSetting->work_day_seconds;

        // ⏱ Update remaining seconds if timer is running
        if ($timer->status === 'running') {
            $secondsPassed = $currentTime->diffInSeconds($timer->updated_at);
            $timer->remaining_seconds = max(0, $timer->remaining_seconds + ($secondsPassed / 2));
        }

        // 🧭 Handle actions
        if ($action === 'resume') {
            $timer->status = 'running';
            $timer->pause_type = 'resume';
        } elseif ($action !== 'tick') {
            $timer->status = 'paused';
            $timer->pause_type = $action;
        }

        // 🕓 Update timestamp and save
        $timer->updated_at = $currentTime;
        $timer->save();

        // 🔢 Calculate elapsed time
        $elapsedSeconds = max(0, $workDaySeconds - $timer->remaining_seconds);


        // 🧾 Log pause/resume event (only if not a tick update)
        if ($action !== 'tick') {
            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'remaining_seconds' => $timer->remaining_seconds,
                'elapsed_seconds'   => $elapsedSeconds,
                'event_time'        => $currentTime,
            ]);
        }

        // 🧠 Return response
        return response()->json([
            'success'           => true,
            'remaining_seconds' => $timer->remaining_seconds,
            'elapsed_seconds'   => $elapsedSeconds,
            'status'            => $timer->status,
            'pause_type'        => $timer->pause_type,
            'notice_status'     => $timer->notice_status,
            'logout'            => $timer->remaining_seconds <= 0
        ]);
    }
}
