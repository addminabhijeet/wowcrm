<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

            // === Added: Create UserTimerPause on login ===
            $user = Auth::user();
            if ($user) {
                $latestTimer = UserTimerLog::where('user_id', $user->id)->latest()->first();
                if ($latestTimer) {
                    UserTimerPause::create([
                        'user_timer_log_id' => $latestTimer->id,
                        'user_id' => $user->id,
                        'status' => 'resumed',
                        'pause_type' => 'login',
                        'remaining_seconds' => $latestTimer->remaining_seconds,
                        'event_time' => now(),
                    ]);
                }
            }
            // =============================================

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

    public function ajaxLogout(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found.']);
            }

            // --- Stop login (mark as inactive) ---
            $user->status = 0;
            $user->save();

            // --- Record pause for timer logs ---
            $latestTimer = UserTimerLog::where('user_id', $user->id)->latest()->first();
            if ($latestTimer) {
                UserTimerPause::create([
                    'user_timer_log_id' => $latestTimer->id,
                    'user_id' => $user->id,
                    'status' => 'paused',
                    'pause_type' => 'logout by senior',
                    'remaining_seconds' => $latestTimer->remaining_seconds,
                    'event_time' => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Junior logged out successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
