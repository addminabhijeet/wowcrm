<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Login;
use App\Models\UserTimerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    const WORK_DAY_SECONDS = 9 * 60 * 60;

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

            $lastTimer = UserTimerLog::where('user_id', Auth::id())
                ->latest()
                ->first();

            if (!$lastTimer || $lastTimer->status === 'completed') {
                UserTimerLog::create([
                    'user_id' => Auth::id(),
                    'login_id' => $login->id,
                    'start_time' => now(),
                    'remaining_seconds' => self::WORK_DAY_SECONDS,
                    'status' => 'running'
                ]);
            } else {
                
                if ($lastTimer->status === 'running') {
                    $seconds_passed = now()->diffInSeconds($lastTimer->updated_at);
                    $lastTimer->remaining_seconds = max(0, $lastTimer->remaining_seconds - $seconds_passed);
                }
                $lastTimer->updated_at = now();
                $lastTimer->save();
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
            $timer = UserTimerLog::where('user_id', $user->id)
                ->latest()
                ->first();

            if ($timer && $timer->status === 'running') {
                $seconds_passed = now()->diffInSeconds($timer->updated_at);
                $timer->remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
            }

            if ($timer) {
                $timer->status = 'paused';
                $timer->pause_type = 'logout';
                $timer->updated_at = now();
                $timer->save();
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
