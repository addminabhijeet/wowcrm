<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTimerLog;

class TimerApiController extends Controller
{
    public function update(Request $request)
    {
        $userId = $request->input('user_id');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Just return current elapsed seconds (or keep counting)
        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        $elapsedSeconds = $timer ? now()->diffInSeconds($timer->created_at) : 0;

        return response()->json([
            'success' => true,
            'elapsed_seconds' => $elapsedSeconds
        ]);
    }
}
