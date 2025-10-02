<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Login;
use App\Models\UserTimerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTimerPause;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.signin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $login = Login::create([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'logged_in_at' => now()
            ]);

            $lastTimer = UserTimerLog::where('user_id', Auth::id());

            if ($lastTimer) {
                UserTimerPause::create([
                    'user_timer_log_id' => $lastTimer->id,
                    'user_id' => $login->id,
                    'status' => 'start',
                    'pause_type' => 'login',
                    'remaining_seconds' => $lastTimer->remaining_seconds,
                    'event_time' => now(),
                ]);
            }
            return redirect()->route('dashboard.index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $latestTimer = UserTimerLog::where('user_id', $user->id);
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
