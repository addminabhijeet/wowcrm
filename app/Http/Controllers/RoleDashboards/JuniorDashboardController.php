<?php

namespace App\Http\Controllers\RoleDashboards;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\UserTimerLog;
use Illuminate\Support\Facades\Auth;

class JuniorDashboardController extends Controller
{
    const WORK_DAY_SECONDS = 9 * 60 * 60;

    public function index()
    {
        $user = Auth::user();

        $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

        if ($timer) {
        if ($timer->status === 'running') {
            $seconds_passed = now()->diffInSeconds($timer->updated_at);
            $remaining_seconds = max(0, $timer->remaining_seconds - $seconds_passed);
        } else {
            $remaining_seconds = $timer->remaining_seconds;
        }
        $elapsed_seconds = self::WORK_DAY_SECONDS - $remaining_seconds;
        $status = $timer->status;
    } else {
        $remaining_seconds = self::WORK_DAY_SECONDS;
        $elapsed_seconds = 0;
        $status = 'running';
    }


        $resumes = Resume::where('status', 'pending_review')->get();

        return view('dashboard.junior', compact(
            'resumes',
            'remaining_seconds',
            'elapsed_seconds',
            'status'
        ));
    }
}
