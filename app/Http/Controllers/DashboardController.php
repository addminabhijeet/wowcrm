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
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds = self::WORK_DAY_SECONDS - $remaining_seconds;
            $status = $timer->status;
        }

        return view('dashboard.senior', compact(
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
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds = self::WORK_DAY_SECONDS - $remaining_seconds;
            $status = $timer->status;
        }

        return view('dashboard.trainer', compact(
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
            $remaining_seconds = $timer->remaining_seconds;
            $elapsed_seconds = self::WORK_DAY_SECONDS - $remaining_seconds;
            $status = $timer->status;
        }

        return view('dashboard.accountant', compact(
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

        $istNow = now('Asia/Kolkata');

        // Fixed daily base time: 20:00 IST
        $today20 = $istNow->copy()->startOfDay()->addHours(20);

        // If before today's 20:00, block the timer
        if ($istNow->lt($today20) && !$timer->start_time) {
            return response()->json([
                'success' => false,
                'message' => 'Timer can start only after 20:00 IST.'
            ]);
        }

        // If no start_time yet, initialize with today’s 20:00
        if (!$timer->start_time) {
            $timer->start_time = $today20;
            $timer->remaining_seconds = self::WORK_DAY_SECONDS;
            $timer->status = 'running';
            $timer->pause_type = 'resume';
            $timer->save();
        }

        // Calculate elapsed & update start_time dynamically
        $secondsPassed = $istNow->diffInSeconds($timer->updated_at);
        if ($timer->status === 'running') {
            $timer->remaining_seconds = max(0, $timer->remaining_seconds - $secondsPassed);
            $timer->start_time = $timer->start_time->copy()->addSeconds($secondsPassed);
        }

        // Check reset threshold
        $gap = $istNow->diffInSeconds($timer->start_time);

        $threshold = 3 * 3600; // always 3 hrs
        if ($gap > $threshold) {
            if ($istNow->isFriday()) {
                // On Friday → reset 72 hrs ahead
                $timer->start_time = $timer->start_time->copy()->addHours(72);
            } else {
                // Other days → reset 24 hrs ahead
                $timer->start_time = $timer->start_time->copy()->addHours(24);
            }
            $timer->remaining_seconds = self::WORK_DAY_SECONDS;
            $timer->status = 'running';
            $timer->pause_type = 'reset';
        }

        // Handle actions
        if ($action === 'resume') {
            $timer->status = 'running';
            $timer->pause_type = 'resume';
        } elseif ($action !== 'tick') {
            $timer->status = 'paused';
            $timer->pause_type = $action;
        }

        $timer->updated_at = $istNow;
        $timer->save();

        $elapsed_seconds = self::WORK_DAY_SECONDS - $timer->remaining_seconds;

        if ($action !== 'tick') {
            UserTimerPause::create([
                'user_timer_log_id' => $timer->id,
                'user_id'           => $user->id,
                'status'            => $timer->status,
                'pause_type'        => $timer->pause_type,
                'remaining_seconds' => $timer->remaining_seconds,
                'event_time'        => $istNow,
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
