<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTimerLog;

class TimerApiController extends Controller
{
    public function update(Request $request)
    {
        // Get latest timer entry for each user
        $latestTimers = UserTimerLog::select('user_id')
            ->groupBy('user_id')
            ->get()
            ->map(function ($entry) {
                $latest = UserTimerLog::where('user_id', $entry->user_id)
                    ->latest()
                    ->first();

                $elapsedSeconds = now()->diffInSeconds($latest->created_at);

                return [
                    'user_id' => $entry->user_id,
                    'elapsed_seconds' => $elapsedSeconds,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $latestTimers,
        ]);
    }
}
