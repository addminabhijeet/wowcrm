<?php

namespace App\Http\Controllers\RoleDashboards;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\UserTimerLog;
use Illuminate\Support\Facades\Auth;

class SeniorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $timer = UserTimerLog::where('user_id', $user->id)
            ->latest()
            ->first();

        $remaining_seconds = $timer ? $timer->remaining_seconds : 9*60*60;
        $elapsed_seconds = 9*60*60 - $remaining_seconds;

        $resumes = Resume::where('status', 'forwarded_to_senior')->get();

        return view('dashboard.senior', compact('resumes', 'remaining_seconds', 'elapsed_seconds'));
    }
}
