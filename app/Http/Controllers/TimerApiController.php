<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTimerLog;

class TimerApiController extends Controller
{
    public function update(Request $request)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Missing user_id'], 400);
        }

        // Get latest timer for that user
        $timer = UserTimerLog::where('user_id', $userId)->latest()->first();

        $elapsedSeconds = now()->diffInSeconds($timer->created_at);

        return response()->json([
            'success' => true,
            'user_id' => $userId,
            'elapsed_seconds' => $elapsedSeconds
        ]);
    }
}
