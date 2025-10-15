<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTimerLog;

class TimerApiController extends Controller
{
    public function update()
    {
        // Get the latest timer (no user required)
        $timer = UserTimerLog::latest()->first();

        // If no timer exists, create one starting now
        if (!$timer) {
            $timer = UserTimerLog::create([
                'remaining_seconds' => 0, // optional
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $elapsedSeconds = now()->diffInSeconds($timer->created_at);

        return response()->json([
            'success' => true,
            'elapsed_seconds' => $elapsedSeconds
        ]);
    }
}
