<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MonthlyTarget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlyTargetController extends Controller
{
    /**
     * Display all monthly targets for all users
     */
    public function index()
    {
        $users = User::whereIn('role', ['senior', 'junior'])
                     ->where('is_deleted', 0)
                     ->get();

        $currentYear = Carbon::now()->year;

        return view('monthly-targets.index', compact('users', 'currentYear'));
    }

    /**
     * Display monthly targets for a specific user
     */
    public function userTargets($userId)
    {
        $user = User::whereIn('role', ['senior', 'junior'])
                    ->where('is_deleted', 0)
                    ->findOrFail($userId);

        $currentYear = Carbon::now()->year;

        // Ensure default targets exist
        MonthlyTarget::ensureDefaults($userId, $currentYear);
        MonthlyTarget::ensureDefaults($userId, $currentYear + 1);

        $monthlyTargets = MonthlyTarget::where('user_id', $userId)
                                       ->where('year', $currentYear)
                                       ->orderBy('month')
                                       ->get()
                                       ->keyBy('month');

        return view('monthly-targets.user-targets', compact('user', 'currentYear', 'monthlyTargets'));
    }

    /**
     * Update monthly target for a user
     */
    public function updateTarget(Request $request, $userId, $year, $month)
    {
        $request->validate([
            'target' => 'required|integer|min:0'
        ]);

        $user = User::findOrFail($userId);

        // Check authorization (user can only edit their own targets or admin can edit all)
        if (Auth::user()->id !== $userId && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $monthlyTarget = MonthlyTarget::updateOrCreate(
            [
                'user_id' => $userId,
                'year' => $year,
                'month' => $month
            ],
            [
                'target' => $request->target
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Target updated successfully',
            'target' => $monthlyTarget
        ]);
    }

    /**
     * Get monthly target for a user (with default 1000)
     */
    public function getTarget($userId, $year, $month)
    {
        $target = MonthlyTarget::getTarget($userId, $year, $month, 1000);

        return response()->json([
            'user_id' => $userId,
            'year' => $year,
            'month' => $month,
            'target' => $target
        ]);
    }

    /**
     * Reset target to default (1000) for a specific month
     */
    public function resetTarget($userId, $year, $month)
    {
        $user = User::findOrFail($userId);

        // Check authorization
        if (Auth::user()->id !== $userId && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        MonthlyTarget::updateOrCreate(
            [
                'user_id' => $userId,
                'year' => $year,
                'month' => $month
            ],
            [
                'target' => 1000
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Target reset to default successfully'
        ]);
    }

    /**
     * Bulk update targets for a user
     */
    public function bulkUpdate(Request $request, $userId)
    {
        $request->validate([
            'targets' => 'required|array',
            'targets.*' => 'integer|min:0',
            'year' => 'required|integer'
        ]);

        $user = User::findOrFail($userId);

        // Check authorization
        if (Auth::user()->id !== $userId && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $year = $request->year;
        $updated = [];

        foreach ($request->targets as $month => $target) {
            $monthlyTarget = MonthlyTarget::updateOrCreate(
                [
                    'user_id' => $userId,
                    'year' => $year,
                    'month' => $month
                ],
                [
                    'target' => $target
                ]
            );
            $updated[$month] = $target;
        }

        return response()->json([
            'success' => true,
            'message' => 'All targets updated successfully',
            'updated' => $updated
        ]);
    }
}
