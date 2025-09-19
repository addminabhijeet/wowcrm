<?php

namespace App\Http\Controllers\RoleDashboards;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\UserTimerLog;
use Illuminate\Support\Facades\Auth;

class JuniorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $timer = UserTimerLog::where('user_id', $user->id)
            ->latest()
            ->first();

        $remaining_seconds = $timer ? $timer->remaining_seconds : 8*60*60;
        $elapsed_seconds = 8*60*60 - $remaining_seconds;

        $resumes = Resume::where('status', 'pending_review')->get();

        return view('dashboard.junior', compact('resumes', 'remaining_seconds', 'elapsed_seconds'));
    }
}
