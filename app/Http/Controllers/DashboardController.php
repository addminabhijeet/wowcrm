<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\UserTimerLog;
use App\Models\UserTimerPause;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;

class DashboardController extends Controller
{
    const WORK_DAY_SECONDS = 9 * 60 * 60;

    public function index()
    {
        $users = User::all();
        return view('dashboard.admin', compact('users'));
    }

    public function junior()
    {
        $user = Auth::user();

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        if ($timer) {
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds = self::WORK_DAY_SECONDS - $remaining_seconds;
            $status = $timer->status;
        }

        return view('dashboard.junior', compact(
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }

    public function senior()
    {
        $user = Auth::user();

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        if ($timer) {
            if ($timer->status === 'running') {
                $seconds_passed = now()->diffInSeconds($timer->updated_at);
                $remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
            } else {
                $remaining_seconds = $timer->remaining_seconds;
            }
            $elapsed_seconds = self::WORK_DAY_SECONDS - $remaining_seconds;
            $status = $timer->status;
        } else {
            $remaining_seconds = self::WORK_DAY_SECONDS;
            $elapsed_seconds = 0;
            $status = 'running';
        }

        $resumes = Resume::where('status', 'forwarded_to_senior')->get();

        return view('dashboard.senior', compact(
            'resumes',
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }

    public function trainer()
    {
        $user = Auth::user();

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        if ($timer) {
            if ($timer->status === 'running') {
                $seconds_passed = now()->diffInSeconds($timer->updated_at);
                $remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
            } else {
                $remaining_seconds = $timer->remaining_seconds;
            }
            $elapsed_seconds = self::WORK_DAY_SECONDS - $remaining_seconds;
            $status = $timer->status;
        } else {
            $remaining_seconds = self::WORK_DAY_SECONDS;
            $elapsed_seconds = 0;
            $status = 'running';
        }


        $resumes = Resume::where('status', 'pending_review')->get();

        return view('dashboard.trainer', compact(
            'resumes',
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }

    public function accountant()
    {
        $user = Auth::user();

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        if ($timer) {
            if ($timer->status === 'running') {
                $seconds_passed = now()->diffInSeconds($timer->updated_at);
                $remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
            } else {
                $remaining_seconds = $timer->remaining_seconds;
            }
            $elapsed_seconds = self::WORK_DAY_SECONDS - $remaining_seconds;
            $status = $timer->status;
        } else {
            $remaining_seconds = self::WORK_DAY_SECONDS;
            $elapsed_seconds = 0;
            $status = 'running';
        }


        $resumes = Resume::where('status', 'pending_review')->get();

        return view('dashboard.accountant', compact(
            'resumes',
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }

    public function customer()
    {
        $user = Auth::user();
        $payments = Payment::where('customer_id', $user->id)->get();

        return view('dashboard.customer', compact('payments'));
    }

    public function updateTimer(Request $request)
    {
        $user   = Auth::user();
        $action = $request->input('action');

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
        if (!$timer) {
            return response()->json(['error' => 'Timer not found'], 404);
        }

        // Current time in IST
        $istNow = now('Asia/Kolkata');

        // Today 6 AM IST
        $ist6am = $istNow->copy()->startOfDay()->addHours(6);

        // Timer's updated_at in IST
        $timerUpdatedIst = $timer->updated_at->copy()->timezone('Asia/Kolkata');

        // Convert times to comparable numeric format YYMMDDHHMMSS
        $istNowNum        = $istNow->format('ymdHis');
        $ist6amNum        = $ist6am->format('ymdHis');
        $timerUpdatedNum  = $timerUpdatedIst->format('ymdHis');

        // Reset timer if last update was before 6 AM today
        if ($timerUpdatedNum < $ist6amNum) {
            $timer->remaining_seconds = self::WORK_DAY_SECONDS;
            $timer->status = 'running';
            $timer->pause_type = 'reset';
            $timer->updated_at = $istNow;
            $timer->save();

            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'remaining_seconds' => $timer->remaining_seconds,
                'event_time'        => $istNow,
            ]);

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

        // Update remaining seconds if timer was running
        if ($timer->status === 'running') {
            $seconds_passed = now()->diffInSeconds($timer->updated_at);
            $timer->remaining_seconds = max(0, $timer->remaining_seconds + $seconds_passed);
        }

        // Handle actions
        if ($action === 'resume') {
            $timer->status = 'running';
            $timer->pause_type = 'resume';
        } elseif ($action !== 'tick') {
            $timer->status = 'paused';
            $timer->pause_type = $action;
        }

        $timer->updated_at = now();
        $timer->save();

        $elapsed_seconds = self::WORK_DAY_SECONDS - $timer->remaining_seconds;

        if ($action !== 'tick') {
            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'remaining_seconds' => $timer->remaining_seconds,
                'event_time'        => now(),
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
