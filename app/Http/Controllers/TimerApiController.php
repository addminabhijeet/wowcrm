<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTimerLog;

class TimerApiController extends Controller
{
    public function update(Request $request)
    {
        // Fetch latest timer for each user
        $latestTimers = UserTimerLog::select('user_id')
            ->groupBy('user_id')
            ->get()
            ->map(function ($user) {
                // Get the latest entry per user
                $timer = UserTimerLog::where('user_id', $user->user_id)
                    ->latest('created_at')
                    ->first();

                return [
                    'user_id' => $timer->user_id,
                    'remaining_seconds' => $timer->remaining_seconds,
                    'status' => $timer->status,
                    'pause_type' => $timer->pause_type,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $latestTimers
        ]);
    }
}
