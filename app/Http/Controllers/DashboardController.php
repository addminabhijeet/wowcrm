<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTimerLog;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTimerPause;

class DashboardController extends Controller
{
    const WORK_DAY_SECONDS = 9 * 60 * 60;

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
        $user   = Auth::user();
        $action = $request->input('action');

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
        if (!$timer) {
            return response()->json(['error' => 'Timer not found'], 404);
        }

        $now = now();
        $istNow = now('Asia/Kolkata');
        $ist6am = $istNow->copy()->startOfDay()->addHours(6);

        // ✅ Reset once if last update is before today's 6 AM
        if ($timer->updated_at->lt($ist6am)) {
            $timer->remaining_seconds = self::WORK_DAY_SECONDS; // 9 hrs
            $timer->status = 'running';
            $timer->pause_type = 'reset';
            $timer->updated_at = $istNow;
            $timer->save();

            return response()->json([
                'success'           => true,
                'remaining_seconds' => $timer->remaining_seconds,
                'elapsed_seconds'   => 0,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'notice_status'     => $timer->notice_status,
                'logout'            => false
            ]);
        }

        // Normal tick/update logic
        if ($timer->status === 'running') {
            $seconds_passed = $now->diffInSeconds($timer->updated_at);
            $timer->remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
        }

        if ($action === 'resume') {
            $timer->status = 'running';
            $timer->pause_type = 'resume';
        } elseif ($action !== 'tick') {
            $timer->status = 'paused';
            $timer->pause_type = $action;
        }

        $timer->updated_at = $now;
        $timer->save();

        $elapsed_seconds = self::WORK_DAY_SECONDS - $timer->remaining_seconds;

        if ($action !== 'tick') {
            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'remaining_seconds' => $timer->remaining_seconds,
                'elapsed_seconds'   => $elapsed_seconds,
                'event_time'        => $now,
            ]);
        }

        return response()->json([
            'success'           => true,
            'remaining_seconds' => $timer->remaining_seconds,
            'elapsed_seconds'   => $elapsed_seconds,
            'status'            => $timer->status,
            'pause_type'        => $timer->pause_type,
            'notice_status'     => $timer->notice_status,
            'logout'            => $timer->remaining_seconds <= 0
        ]);
    }
}
