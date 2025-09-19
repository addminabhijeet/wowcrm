<?php

namespace App\Http\Controllers\Auth;
use App\Models\Login;
use App\Models\UserTimerLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.signin');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
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

            // Start 8-hour timer
            UserTimerLog::create([
                'user_id' => Auth::id(),
                'login_id' => $login->id,
                'start_time' => now(),
                'remaining_seconds' => 8*60*60,
                'status' => 'running'
            ]);

            return redirect()->route('dashboard.index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }


    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }



}
