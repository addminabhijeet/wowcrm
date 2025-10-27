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
use Carbon\Carbon;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        return view('dashboard.admin', compact('users','newUsers'));
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
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Fetch the latest timer record by start_time (not ID)
        $latestLog = UserTimerLog::where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->first();

        // Default to 0 if no record exists
        $buttonStatus = $latestLog ? (int) $latestLog->button_status : 0;

        return response()->json([
            'user_id' => $user->id,
            'button_status' => $buttonStatus
        ]);
    }

    public function getLatestPauseTypes()
    {
        $users = User::where('role', 'junior')->get();

        $data = $users->map(function ($user) {
            $latestLog = UserTimerLog::where('user_id', $user->id)
                ->orderBy('start_time', 'desc')
                ->first();

            return [
                'user_id' => $user->id,
                'pause_type' => $latestLog ? $latestLog->pause_type : null,
            ];
        });

        return response()->json($data);
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
                'pause_type'        => 'resume',
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

    public function checkPauseButtons(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            // Get the latest timer log for today
            $latestLog = UserTimerLog::where('user_id', $user->id)
                ->whereDate('created_at', now()->startOfDay())
                ->latest('created_at')
                ->first();

            return response()->json([
                'pause_type' => $latestLog->pause_type ?? null,
                'status'     => $latestLog->status ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function checkPauseButtonsSenior(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // Optional: fallback to logged-in user
            if (!$userId) {
                $user = Auth::user();
                $userId = $user->id ?? null;
            }

            if (!$userId) {
                return response()->json(['error' => 'User not found'], 401);
            }

            // Get the latest timer log for today
            $latestLog = UserTimerLog::where('user_id', $userId)
                ->whereDate('created_at', now()->startOfDay())
                ->latest('created_at')
                ->first();

            return response()->json([
                'pause_type' => $latestLog->pause_type ?? null,
                'status'     => $latestLog->status ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
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

        // Current time
        $currentTime = now();

        // Fetch work day duration from settings
        $timerSetting = TimerSetting::first();
        if (!$timerSetting) {
            return response()->json(['error' => 'Timer settings not configured'], 500);
        }

        $workDaySeconds = $timerSetting->work_day_seconds;

        // Update remaining seconds if timer is running
        if ($timer->status === 'running') {
            $secondsPassed = $currentTime->diffInSeconds($timer->updated_at);
            $timer->remaining_seconds = max(0, $timer->remaining_seconds + ($secondsPassed / 2));
        }

        // Store previous status before any change
        $previousStatus = $timer->status;
        $previousPauseType = $timer->pause_type;

        // Helper: avoid duplicate pause
        $createPauseIfChanged = function ($status, $pauseType, $remainingSeconds) use ($timer, $user) {
            $lastPause = UserTimerPause::where('user_id', $user->id)
                ->latest('id')
                ->first();

            // Only create if values differ from last
            if (
                !$lastPause ||
                $lastPause->status !== $status ||
                $lastPause->pause_type !== $pauseType ||
                $lastPause->remaining_seconds != $remainingSeconds
            ) {

                UserTimerPause::create([
                    'user_timer_log_id' => $timer->id,
                    'user_id'           => $user->id,
                    'status'            => $status,
                    'pause_type'        => $pauseType,
                    'remaining_seconds' => $remainingSeconds,
                    'event_time'        => now(),
                ]);
            }
        };

        if ($action === 'resume' || $action === 'resumebreak') {
            if ($timer->status === 'paused' && in_array($timer->pause_type, ['break', 'lunch', 'tea'])) {
                if ($action !== 'resumebreak') {
                    return; // silently skip if normal resume is triggered
                }
            }

            $timer->status = 'running';
            $timer->pause_type = 'resume';

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
                $createPauseIfChanged('running', $action, $timer->remaining_seconds);
            }

            if ($previousStatus === 'paused') {
                $createPauseIfChanged('running', 'resume', $timer->remaining_seconds);
            }
        } elseif (in_array($action, ['lunch', 'tea', 'break'])) {
            $timer->status = 'paused';
            $timer->pause_type = $action;

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
                $createPauseIfChanged('paused', $action, $timer->remaining_seconds);
            }

            $createPauseIfChanged('paused', $action, $timer->remaining_seconds);
        } elseif ($action !== 'tick') {
            $timer->status = 'paused';
            $timer->pause_type = 'inactive';

            $latestLog = UserTimerLog::where('user_id', $user->id)
                ->latest('id')
                ->first();

            if ($latestLog) {
                if (!in_array($latestLog->pause_type, ['lunch', 'tea', 'break'])) {
                    $latestLog->update([
                        'remaining_seconds' => $timer->remaining_seconds,
                        'status'            => 'paused',
                        'pause_type'        => 'inactive',
                    ]);
                    $createPauseIfChanged('paused', 'inactive', $timer->remaining_seconds);
                }
            } else {
                $createPauseIfChanged('paused', 'inactive', $timer->remaining_seconds);
            }
        }


        // Update timestamp and save
        $timer->updated_at = $currentTime;
        $timer->save();

        // Calculate elapsed time
        $elapsedSeconds = max(0, $workDaySeconds - $timer->remaining_seconds);

        // Return response
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



    public function editall()
    {
        // Get only users that have SMTP settings
        $juniorUsers = User::whereHas('smtpSetting')->get();

        return view('smtp.editall', compact('juniorUsers'));
    }


    public function add()
    {
        // Get all user IDs that already have SMTP settings
        $smtpUserIds = SmtpSetting::pluck('user_id')->toArray();

        // Get users who do not have SMTP settings
        $users = User::whereNotIn('id', $smtpUserIds)->get();

        return view('smtp.add', compact('users'));
    }

    // Show the form to edit SMTP settings
    public function edit($userId)
    {
        $smtp = SmtpSetting::where('user_id', $userId)->first(); // Get SMTP for specific user
        $users = User::all(); // Keep for dropdown or selection if needed

        return view('smtp.edit', compact('smtp', 'users'));
    }

    public function addupdate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mailer' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|email',
            'password' => 'nullable|string',
            'encryption' => 'required|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
        ]);

        // Find existing SMTP setting for this user or create new
        $smtp = SmtpSetting::firstOrNew(['user_id' => $request->user_id]);

        $smtp->user_id = $request->user_id;
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

        return redirect()->route('smtp.editall')->with('success', 'SMTP settings saved successfully!');
    }

    public function update(Request $request, $userId)
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

        $smtp = SmtpSetting::where('user_id', $userId)->firstOrFail();

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
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $smtp = SmtpSetting::first();
        if (!$smtp) {
            return response()->json(['message' => 'No SMTP settings found.'], 400);
        }

        // Determine encryption
        $encryption = strtolower($smtp->encryption);
        if ($encryption === 'ssl/tls') $encryption = 'ssl';
        if (!in_array($encryption, ['ssl', 'tls'])) {
            $encryption = null;
        }

        $testEmail = $request->test_email;

        try {
            // Configure and use mailer directly
            config([
                'mail.mailers.custom_smtp' => [
                    'transport' => 'smtp',
                    'host' => $smtp->host,
                    'port' => $smtp->port,
                    'encryption' => $encryption,
                    'username' => $smtp->username,
                    'password' => decrypt($smtp->password),
                    'timeout' => 30,
                    'auth_mode' => 'login',
                ]
            ]);

            // Use the custom mailer
            Mail::mailer('custom_smtp')->send([], [], function ($message) use ($testEmail, $smtp) {
                $message->from($smtp->from_address, $smtp->from_name)
                    ->to($testEmail)
                    ->subject('SMTP Test Email - Synergie Systems CRM')
                    ->setBody('This is a test email from Synergie Systems CRM.');
            });

            return response()->json([
                'message' => "Test email sent successfully to {$testEmail}!"
            ]);
        } catch (TransportExceptionInterface $e) {
            return response()->json([
                'message' => 'SMTP Transport Error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send test email.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
