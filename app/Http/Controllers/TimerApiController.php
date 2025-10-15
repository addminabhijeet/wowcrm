<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTimerLog;

class TimerApiController extends Controller
{
    public function getAllTimers()
    {
        // Get all distinct user_ids
        $userIds = UserTimerLog::select('user_id')->distinct()->pluck('user_id');

        $data = $userIds->map(function ($userId) {
            // For each user, get their latest log
            $latestLog = UserTimerLog::where('user_id', $userId)
                ->latest('id')
                ->first();

            return [
                'user_id' => $latestLog->user_id,
                'remaining_seconds' => (int) $latestLog->remaining_seconds,
                'status' => $latestLog->status,
                'pause_type' => $latestLog->pause_type
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
