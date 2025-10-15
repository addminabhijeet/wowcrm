<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTimerLog;
use Carbon\Carbon;

class TimerApiController extends Controller
{
    public function getAllTimers()
    {
        // Get the latest timer entry for each user
        $latestTimers = UserTimerLog::select('user_id')
            ->groupBy('user_id')
            ->get()
            ->map(function ($userTimer) {
                // Get the latest log for each user
                $latestLog = UserTimerLog::where('user_id', $userTimer->user_id)
                    ->latest('created_at')
                    ->first();

                // Calculate elapsed seconds since start_time or created_at
                $startTime = $latestLog->start_time ? Carbon::parse($latestLog->start_time) : $latestLog->created_at;
                $elapsed = $startTime->diffInSeconds(Carbon::now());

                return [
                    'user_id' => $latestLog->user_id,
                    'status' => $latestLog->status,
                    'remaining_seconds' => $latestLog->remaining_seconds,
                    'elapsed_seconds' => $elapsed,
                    'start_time' => $startTime->toDateTimeString(),
                    'updated_at' => $latestLog->updated_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'total_users' => $latestTimers->count(),
            'data' => $latestTimers
        ]);
    }
}
