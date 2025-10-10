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
use Illuminate\Support\Facades\Mail;
use App\Models\SmtpSetting;

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

    public function startTimerHide(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            $today = now()->startOfDay();

            // Check if timer exists for today
            $existingTimer = UserTimerLog::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->first();

            return response()->json([
                'exists' => $existingTimer ? true : false,
                'timer'  => $existingTimer ?? null
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
        $userId = $request->input('user_id');
        $user = $userId ? User::find($userId) : Auth::user();
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

        // Store previous status before any change
        $previousStatus = $timer->status;
        $previousPauseType = $timer->pause_type;

        if ($action === 'resume' || $action === 'resumebreak') {
            // If currently paused for break, lunch, or tea
            if ($timer->status === 'paused' && in_array($timer->pause_type, ['break', 'lunch', 'tea'])) {
                // Allow resume ONLY if action is 'resumebreak'
                if ($action !== 'resumebreak') {
                    return; // silently skip if normal resume is triggered
                }
            }

            $timer->status = 'running';
            $timer->pause_type = 'resume';

            // Get latest timer log for the user (exclude non-working pauses)
            $latestLog = UserTimerLog::where('user_id', $user->id)
                ->whereNotIn('pause_type', ['lunch', 'break', 'tea'])
                ->latest('id')
                ->first();

            if ($latestLog) {
                $latestLog->update([
                    'remaining_seconds' => $timer->remaining_seconds,
                    'status'            => 'running',
                    'pause_type'        => 'resume',
                ]);
            } else {
                UserTimerPause::create([
                    'user_timer_log_id' => $timer->id,
                    'user_id'           => $user->id,
                    'status'            => 'running',
                    'pause_type'        => $action,
                    'remaining_seconds' => $timer->remaining_seconds,
                    'event_time'        => now(),
                ]);
            }

            // ✅ Create UserTimerPause entry only if previously paused
            if ($previousStatus === 'paused') {
                UserTimerPause::create([
                    'user_timer_log_id' => $timer->id,
                    'user_id'           => $user->id,
                    'status'            => 'running',
                    'pause_type'        => 'resume',
                    'remaining_seconds' => $timer->remaining_seconds,
                    'event_time'        => now(),
                ]);
            }
        } elseif (in_array($action, ['lunch', 'tea', 'break'])) {

            $pauseLabels = [
                'lunch' => 'Lunch Break',
                'tea'   => 'Tea Break',
                'break' => 'Short Break',
            ];

            $timer->status = 'paused';
            $timer->pause_type = $action;

            // Get latest timer log for the user
            $latestLog = UserTimerLog::where('user_id', $user->id)
                ->latest('id')
                ->first();

            if ($latestLog) {
                $latestLog->update([
                    'remaining_seconds' => $timer->remaining_seconds,
                    'status'            => 'paused',
                    'pause_type'        => $action,
                ]);
            } else {
                UserTimerPause::create([
                    'user_timer_log_id' => $timer->id,
                    'user_id'           => $user->id,
                    'status'            => 'paused',
                    'pause_type'        => $action,
                    'remaining_seconds' => $timer->remaining_seconds,
                    'event_time'        => now(),
                ]);
            }

            // Log the pause in UserTimerPause
            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => 'paused',
                'pause_type'        => $action,
                'remaining_seconds' => $timer->remaining_seconds,
                'event_time'        => now(),
            ]);
        } elseif ($action !== 'tick') {
            // Default inactive pause (manual stop or idle)
            $timer->status = 'paused';
            $timer->pause_type = 'inactive';

            $latestLog = UserTimerLog::where('user_id', $user->id)
                ->latest('id')
                ->first();

            if ($latestLog) {
                // Check if already paused for lunch/tea/break
                if (!in_array($latestLog->pause_type, ['lunch', 'tea', 'break'])) {
                    // Update only if not in lunch/tea/break
                    $latestLog->update([
                        'remaining_seconds' => $timer->remaining_seconds,
                        'status'            => 'paused',
                        'pause_type'        => 'inactive',
                    ]);

                    // Log pause event for audit only when updated
                    UserTimerPause::create([
                        'user_timer_log_id' => $timer->id,
                        'user_id'           => $user->id,
                        'status'            => 'paused',
                        'pause_type'        => 'inactive',
                        'remaining_seconds' => $timer->remaining_seconds,
                        'event_time'        => now(),
                    ]);
                }
            } else {
                // Create fallback log if none found
                UserTimerPause::create([
                    'user_timer_log_id' => $timer->id,
                    'user_id'           => $user->id,
                    'status'            => 'paused',
                    'pause_type'        => 'inactive',
                    'remaining_seconds' => $timer->remaining_seconds,
                    'event_time'        => now(),
                ]);
            }
        }



        // 🕓 Update timestamp and save
        $timer->updated_at = $currentTime;
        $timer->save();

        // 🔢 Calculate elapsed time
        $elapsedSeconds = max(0, $workDaySeconds - $timer->remaining_seconds);

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

    // Show the form to edit SMTP settings
    public function edit()
    {
        $smtp = SmtpSetting::first(); // Assume single record
        return view('smtp.edit', compact('smtp'));
    }

    // Update SMTP settings
    public function update(Request $request)
    {
        $request->validate([
            'mailer' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|email',
            'password' => 'nullable|string',
            'encryption' => 'required|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
        ]);

        $smtp = SmtpSetting::first();
        if (!$smtp) {
            $smtp = new SmtpSetting();
        }

        $smtp->mailer = $request->mailer;
        $smtp->host = $request->host;
        $smtp->port = $request->port;
        $smtp->username = $request->username;
        if ($request->filled('password')) {
            $smtp->password = encrypt($request->password); // encrypt password
        }
        $smtp->encryption = $request->encryption;
        $smtp->from_address = $request->from_address;
        $smtp->from_name = $request->from_name;

        $smtp->save();

        return redirect()->back()->with('success', 'SMTP settings updated successfully!');
    }

    public function test(Request $request)
    {
        $smtp = SmtpSetting::first();
        if (!$smtp) {
            return response()->json(['message' => 'No SMTP settings found.'], 400);
        }

        config([
            'mail.mailers.smtp.transport' => 'smtp', // always 'smtp'
            'mail.mailers.smtp.host' => $smtp->host,
            'mail.mailers.smtp.port' => $smtp->port,
            'mail.mailers.smtp.username' => $smtp->username,
            'mail.mailers.smtp.password' => decrypt($smtp->password),
            'mail.mailers.smtp.encryption' => $smtp->encryption,
            'mail.from.address' => $smtp->from_address,
            'mail.from.name' => $smtp->from_name,
        ]);


        $testEmail = $request->input('test_email');

        try {
            Mail::raw('This is a test email from Synergie Systems CRM.', function ($message) use ($testEmail) {
                $message->to($testEmail)->subject('SMTP Test Email');
            });

            return response()->json(['message' => "Test email sent successfully to {$testEmail}!"]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send test email: ' . $e->getMessage()], 500);
        }
    }
}
