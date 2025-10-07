<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Login;
use App\Models\UserTimerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTimerPause;
use App\Models\TimerSetting;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.signin');
    }

    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Record login info
            $login = Login::create([
                'user_id'     => Auth::id(),
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->header('User-Agent'),
                'logged_in_at'=> now(),
            ]);

            // Fetch work day seconds from settings dynamically
            $settings = TimerSetting::first();
            if (!$settings) {
                return response()->json(['error' => 'Timer settings not configured'], 500);
            }
            $workDaySeconds = $settings->work_day_seconds;

            // Check if user already has a timer
            $lastTimer = UserTimerLog::where('user_id', Auth::id())->latest()->first();

            if (!$lastTimer) {
                $lastTimer = UserTimerLog::create([
                    'user_id'           => Auth::id(),
                    'login_id'          => $login->id,
                    'start_time'        => now(),
                    'remaining_seconds' => $workDaySeconds,
                    'status'            => 'paused',
                ]);
            }

            // Log a pause/resume event
            if ($lastTimer) {
                UserTimerPause::create([
                    'user_timer_log_id' => $lastTimer->id,
                    'user_id'           => Auth::id(),
                    'status'            => 'start',
                    'pause_type'        => 'login',
                    'remaining_seconds' => $lastTimer->remaining_seconds,
                    'event_time'        => now(),
                ]);
            }

            // Redirect based on user role
            $role = Auth::user()->role;

            switch ($role) {
                case 'junior':
                    return redirect()->route('dashboard.junior');
                case 'senior':
                    return redirect()->route('dashboard.senior');
                case 'customer':
                    return redirect()->route('dashboard.customer');
                case 'accountant':
                    return redirect()->route('dashboard.accountant');
                case 'trainer':
                    return redirect()->route('dashboard.trainer');
                case 'admin':
                    return redirect()->route('dashboard.admin');
                default:
                    abort(403, 'Unauthorized action.');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $latestTimer = UserTimerLog::where('user_id', $user->id)->latest()->first();
            if ($latestTimer) {
                UserTimerPause::create([
                    'user_timer_log_id' => $latestTimer->id,
                    'user_id' => $user->id,
                    'status' => 'paused',
                    'pause_type' => 'logout',
                    'remaining_seconds' => $latestTimer->remaining_seconds,
                    'event_time' => now(),
                ]);
            }
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
