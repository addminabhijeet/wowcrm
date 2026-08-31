<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GoogleSheetData;
use App\Models\MonthlyTarget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TargetAnalyticsController extends Controller
{
    /**
     * Ensure default targets exist for current and future years
     * Auto-creates for: current year + 2 future years
     */
    public function ensureDefaultsForFutureYears()
    {
        $currentYear = Carbon::now()->year;

        // Get all active users
        $users = User::whereIn('role', ['senior', 'junior'])
                     ->where('is_deleted', 0)
                     ->get();

        // Create defaults for current year + 2 future years
        foreach ($users as $user) {
            for ($year = $currentYear; $year <= $currentYear + 2; $year++) {
                MonthlyTarget::ensureDefaults($user->id, $year);
            }
        }
    }

    /**
     * Show target analytics dashboard
     * Displays: Target Given vs Target Achieved for each month
     */
    public function dashboard()
    {
        // Ensure defaults exist for all future years
        $this->ensureDefaultsForFutureYears();

        $currentYear = Carbon::now()->year;
        $cacheKey = "target_analytics_dashboard_{$currentYear}";

        // Check cache first (5 minutes)
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return view('target-analytics.dashboard', $cached);
        }

        $users = User::whereIn('role', ['senior', 'junior'])
                     ->where('is_deleted', 0)
                     ->get();

        // Pre-calculate all summaries to avoid N+1 queries in view
        $usersSummary = [];
        foreach ($users as $user) {
            $usersSummary[$user->id] = $this->getYearlySummary($user->id, $currentYear);
        }

        $data = compact('users', 'currentYear', 'usersSummary');

        // Cache for 5 minutes
        Cache::put($cacheKey, $data, 300);

        return view('target-analytics.dashboard', $data);
    }

    /**
     * Show monthly comparison for a specific user
     * Target Given vs Target Achieved
     */
    public function userAnalytics($userId)
    {
        // Ensure defaults exist for all future years
        $this->ensureDefaultsForFutureYears();

        $user = User::whereIn('role', ['senior', 'junior'])
                    ->where('is_deleted', 0)
                    ->findOrFail($userId);

        $currentYear = Carbon::now()->year;
        $yearParam = request('year', $currentYear);

        // Get all monthly data
        $monthlyData = $this->getMonthlyComparison($userId, $yearParam);

        // Calculate yearly summary
        $summary = $this->getYearlySummary($userId, $yearParam);

        return view('target-analytics.user-analytics', compact(
            'user',
            'currentYear',
            'yearParam',
            'monthlyData',
            'summary'
        ));
    }

    /**
     * Get comparison data for all months in a year
     */
    public function getMonthlyComparison($userId, $year)
    {
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $user = User::find($userId);
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            // Get target given (from new system first, then fallback to old system)
            $targetGiven = $this->getTargetGiven($userId, $year, $month, $user);

            // Get target achieved (from GoogleSheetData)
            $targetAchieved = $this->getTargetAchieved($userId, $year, $month, $user->role);

            // Calculate variance
            $variance = $targetAchieved - $targetGiven;
            $variancePercent = $targetGiven > 0 ? (($variance / $targetGiven) * 100) : 0;
            $status = $this->getStatus($targetAchieved, $targetGiven);

            $data[$month] = [
                'month_name' => $monthNames[$month],
                'month_number' => $month,
                'target_given' => (int) $targetGiven,
                'target_achieved' => (int) $targetAchieved,
                'variance' => (int) $variance,
                'variance_percent' => round($variancePercent, 2),
                'status' => $status,
                'achievement_percent' => $targetGiven > 0 ? round(($targetAchieved / $targetGiven) * 100, 2) : 0
            ];
        }

        return $data;
    }

    /**
     * Get target given for a user/month
     * Priority: New system (monthly_targets) → Old system (users table)
     */
    public function getTargetGiven($userId, $year, $month, $user)
    {
        // Try new system first
        $monthlyTarget = MonthlyTarget::where('user_id', $userId)
                                     ->where('year', $year)
                                     ->where('month', $month)
                                     ->first();

        if ($monthlyTarget) {
            return $monthlyTarget->target;
        }

        // Fallback to old system
        return $this->getTargetFromOldSystem($userId, $year, $month, $user);
    }

    /**
     * Get target from old system (users.target and users.target_date)
     */
    public function getTargetFromOldSystem($userId, $year, $month, $user)
    {
        if (!$user->target || !$user->target_date) {
            return 0;
        }

        $targetValues = array_map('trim', explode('|', $user->target));
        $targetDates = array_map('trim', explode('|', $user->target_date));

        $targetIndex = null;
        $yearMonth = sprintf("%04d-%02d", $year, $month);

        foreach ($targetDates as $index => $date) {
            $monthPart = preg_match('/^\d{4}-\d{2}$/', $date)
                ? $date
                : Carbon::parse($date)->format('Y-m');

            if ($monthPart === $yearMonth) {
                $targetIndex = $index;
                break;
            }
        }

        if ($targetIndex !== null && isset($targetValues[$targetIndex])) {
            return (int) $targetValues[$targetIndex];
        }

        // Return first target as default if exists
        return isset($targetValues[0]) ? (int) $targetValues[0] : 0;
    }

    /**
     * Get target achieved from GoogleSheetData
     * Sums Amount field for user in specific month
     */
    public function getTargetAchieved($userId, $year, $month, $role)
    {
        // Use exact REGEXP pattern from CallReportController::junior()
        if ($role === 'junior') {
            $pattern = "created_by REGEXP '^{$userId}\\\\|junior:[0-9]+\\\\|senior:[0-9]+\\\\|accountant(.*)?$'";
        } else {
            // For senior role
            $pattern = "created_by REGEXP '^{$userId}\\\\|senior:[0-9]+\\\\|senior:[0-9]+\\\\|accountant(.*)?$'";
        }

        $achieved = GoogleSheetData::whereRaw($pattern)
                                   ->whereYear('updated_at', $year)
                                   ->whereMonth('updated_at', $month)
                                   ->sum('Amount');

        return (int) $achieved;
    }

    /**
     * Determine status based on achievement
     */
    public function getStatus($achieved, $given)
    {
        if ($given == 0) {
            return 'no_target';
        }

        $percent = ($achieved / $given) * 100;

        if ($percent >= 100) {
            return 'achieved';
        } elseif ($percent >= 75) {
            return 'good';
        } elseif ($percent >= 50) {
            return 'partial';
        } else {
            return 'below';
        }
    }

    /**
     * Get yearly summary
     * Optimized with batch queries and individual caching
     */
    public function getYearlySummary($userId, $year)
    {
        $cacheKey = "user_summary_{$userId}_{$year}";

        // Check individual user cache (1 hour)
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        $user = User::find($userId);
        $totalGiven = 0;
        $totalAchieved = 0;

        // Batch load all monthly targets for the year (single query)
        $monthlyTargets = MonthlyTarget::where('user_id', $userId)
                                       ->where('year', $year)
                                       ->get()
                                       ->keyBy('month');

        // Batch load all achieved amounts for the year (single query)
        // Use exact REGEXP pattern from CallReportController::junior()
        if ($user->role === 'junior') {
            $pattern = "created_by REGEXP '^{$userId}\\\\|junior:[0-9]+\\\\|senior:[0-9]+\\\\|accountant(.*)?$'";
        } else {
            // For senior role
            $pattern = "created_by REGEXP '^{$userId}\\\\|senior:[0-9]+\\\\|senior:[0-9]+\\\\|accountant(.*)?$'";
        }

        $achievedByMonth = GoogleSheetData::whereRaw($pattern)
                                          ->whereYear('updated_at', $year)
                                          ->selectRaw('MONTH(updated_at) as month, SUM(Amount) as total')
                                          ->groupBy('month')
                                          ->get()
                                          ->keyBy('month');

        // Calculate totals efficiently
        for ($month = 1; $month <= 12; $month++) {
            // Get target given
            if (isset($monthlyTargets[$month])) {
                $given = $monthlyTargets[$month]->target;
            } else {
                $given = $this->getTargetFromOldSystem($userId, $year, $month, $user);
            }
            $totalGiven += $given;

            // Get target achieved
            $totalAchieved += $achievedByMonth[$month]->total ?? 0;
        }

        $variance = $totalAchieved - $totalGiven;
        $variancePercent = $totalGiven > 0 ? (($variance / $totalGiven) * 100) : 0;
        $achievementPercent = $totalGiven > 0 ? (($totalAchieved / $totalGiven) * 100) : 0;

        $result = [
            'total_given' => (int) $totalGiven,
            'total_achieved' => (int) $totalAchieved,
            'variance' => (int) $variance,
            'variance_percent' => round($variancePercent, 2),
            'achievement_percent' => round($achievementPercent, 2),
            'status' => $this->getStatus($totalAchieved, $totalGiven)
        ];

        // Cache individual summary for 1 hour
        Cache::put($cacheKey, $result, 3600);

        return $result;
    }

    /**
     * Export monthly data as JSON (for charts/reports)
     */
    public function exportJson($userId)
    {
        // Ensure defaults exist for all future years
        $this->ensureDefaultsForFutureYears();

        $user = User::findOrFail($userId);
        $year = request('year', Carbon::now()->year);

        $monthlyData = $this->getMonthlyComparison($userId, $year);
        $summary = $this->getYearlySummary($userId, $year);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'year' => $year,
            'summary' => $summary,
            'monthly' => $monthlyData
        ]);
    }

    /**
     * Compare two users' performance
     */
    public function compare(Request $request)
    {
        // Ensure defaults exist for all future years
        $this->ensureDefaultsForFutureYears();

        $user1Id = $request->input('user1_id');
        $user2Id = $request->input('user2_id');
        $year = $request->input('year', Carbon::now()->year);

        $users = User::whereIn('id', [$user1Id, $user2Id])
                     ->get()
                     ->keyBy('id');

        if ($users->count() != 2) {
            abort(404, 'One or both users not found');
        }

        $user1Data = $this->getMonthlyComparison($user1Id, $year);
        $user2Data = $this->getMonthlyComparison($user2Id, $year);

        $user1Summary = $this->getYearlySummary($user1Id, $year);
        $user2Summary = $this->getYearlySummary($user2Id, $year);

        $currentYear = Carbon::now()->year;

        return view('target-analytics.compare', compact(
            'users',
            'year',
            'currentYear',
            'user1Data',
            'user2Data',
            'user1Summary',
            'user2Summary'
        ));
    }

    /**
     * Show all users' monthly analytics
     * Optimized to pre-calculate all summaries
     */
    public function allUsersAnalytics()
    {
        // Ensure defaults exist for all future years
        $this->ensureDefaultsForFutureYears();

        $currentYear = Carbon::now()->year;
        $year = request('year', $currentYear);
        $cacheKey = "target_analytics_all_users_{$year}";

        // Check cache first (5 minutes)
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return view('target-analytics.all-users', $cached);
        }

        $users = User::whereIn('role', ['senior', 'junior'])
                     ->where('is_deleted', 0)
                     ->get();

        // Pre-calculate all summaries (single pass)
        $usersData = [];
        foreach ($users as $user) {
            $summary = $this->getYearlySummary($user->id, $year);
            $usersData[] = [
                'user' => $user,
                'summary' => $summary
            ];
        }

        // Sort by achievement percent (descending) - only once
        usort($usersData, function($a, $b) {
            return $b['summary']['achievement_percent'] <=> $a['summary']['achievement_percent'];
        });

        $data = compact('usersData', 'currentYear', 'year');

        // Cache for 5 minutes
        Cache::put($cacheKey, $data, 300);

        return view('target-analytics.all-users', $data);
    }
}
