<?php

namespace App\Http\Controllers\RoleDashboards;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $trainings = Training::where('trainer_id', $user->id)
            ->with(['customer', 'resume'])
            ->get();

        return view('dashboard.trainer', compact('trainings'));
    }
}
