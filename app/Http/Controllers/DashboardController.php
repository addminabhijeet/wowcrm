<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\Payment;
use App\Models\Training;
use App\Models\User;
use App\Models\UserTimerLog;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
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


    public function updateTimer(Request $request)
    {
        $timer = UserTimerLog::where('user_id', Auth::id())
                    ->latest()
                    ->first();

        if (!$timer) {
            return response()->json(['error' => 'No timer found'], 404);
        }

        $action = $request->input('action');

        if ($action === 'pause') {
            // Mark timer as paused
            $timer->status = 'paused';
            $timer->pause_type = $request->input('pause_type');
            $timer->save();

            // Create a pause entry
            $timer->pauses()->create([
                'pause_type' => $request->input('pause_type'),
                'started_at' => now()
            ]);

        } elseif ($action === 'resume') {
            // Close the last pause entry
            $lastPause = $timer->pauses()
                ->whereNull('ended_at')
                ->latest()
                ->first();

            if ($lastPause) {
                $lastPause->ended_at = now();
                $lastPause->duration_seconds = $lastPause->ended_at->diffInSeconds($lastPause->started_at);
                $lastPause->save();
            }

            // Resume timer
            $timer->status = 'running';
            $timer->pause_type = null;
            $timer->save();

        } elseif ($action === 'tick') {
            if ($timer->status === 'running') {
                $timer->remaining_seconds = max(0, $timer->remaining_seconds - 60);

                if ($timer->remaining_seconds <= 0) {
                    // Auto-stop timer
                    $timer->status = 'completed';
                    $timer->save();

                    // Optional: Auto-logout user when timer ends
                    Auth::logout();
                    session()->invalidate();
                    session()->regenerateToken();

                    return response()->json([
                        'remaining_seconds' => 0,
                        'status' => 'completed',
                        'pause_type' => null,
                        'logout' => true
                    ]);
                }

                $timer->save();
            }
        }

        return response()->json([
            'remaining_seconds' => $timer->remaining_seconds,
            'status' => $timer->status,
            'pause_type' => $timer->pause_type
        ]);
    }

}
