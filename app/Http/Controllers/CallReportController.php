<?php

namespace App\Http\Controllers;

use App\Models\GoogleSheetData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class CallReportController extends Controller
{
    public function index() {}

    public function senior(Request $request)
    {
        $user = Auth::user();
        $createdByKey = "{$user->id}|senior";

        // ================================
        // Main logic with LIKE filters
        // ================================

        // Total calls for this senior (including hierarchical keys)
        $totalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")->count();

        // Total "Called & Mailed" calls
        $calledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total "Ready To Paid" calls
        $readyToPaidCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where('Exe_Remarks', 'Ready To Paid')
            ->count();

        // Total other calls (excluding Called & Mailed)
        $otherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Selected date (default today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by this senior and date
        $query = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate);

        // Selected date totals
        $StotalCalls = $query->count();
        $ScalledAndMailedCalls = (clone $query)->where('Exe_Remarks', 'Called & Mailed')->count();
        $SreadyToPaidCalls = (clone $query)->where('Exe_Remarks', 'Ready To Paid')->count();
        $SotherCalls = (clone $query)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('Exe_Remarks', '<>', 'Called & Mailed')
                        ->where('Exe_Remarks', '<>', 'Ready To Paid');
                })
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();



        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Ready To Paid" counts
        $hourlyReadyToPaid = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise other calls
        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('Exe_Remarks', '<>', 'Called & Mailed')
                        ->where('Exe_Remarks', '<>', 'Ready To Paid');
                })
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();



        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $r10to11am = $hourlyReadyToPaid[10] ?? 0;
        $r11to12pm = $hourlyReadyToPaid[11] ?? 0;
        $r12to1pm  = $hourlyReadyToPaid[12] ?? 0;
        $r1to2pm   = $hourlyReadyToPaid[13] ?? 0;
        $r2to3pm   = $hourlyReadyToPaid[14] ?? 0;
        $r3to4pm   = $hourlyReadyToPaid[15] ?? 0;
        $r4to5pm   = $hourlyReadyToPaid[16] ?? 0;
        $r5to6pm   = $hourlyReadyToPaid[17] ?? 0;
        $r6to7pm   = $hourlyReadyToPaid[18] ?? 0;
        $r7to8pm   = $hourlyReadyToPaid[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.senior', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'readyToPaidCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SreadyToPaidCalls',
            'SotherCalls',
            'selectedDate',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'r10to11am',
            'r11to12pm',
            'r12to1pm',
            'r1to2pm',
            'r2to3pm',
            'r3to4pm',
            'r4to5pm',
            'r5to6pm',
            'r6to7pm',
            'r7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }


    public function seniormonthly(Request $request)
    {
        $user = Auth::user();
        $createdByKey = "{$user->id}|senior";

        // Selected month (default current month in YYYY-MM)
        $selectedMonth = $request->input('selected_month', date('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);

        // Total calls for this senior in the selected month (including hierarchical keys)
        $MtotalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->count();

        // Total "Called & Mailed" calls
        $McalledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total "Ready To Paid" calls
        $MreadyToPaidCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->count();

        // Total other calls (not "Called & Mailed" or "Ready To Paid")
        $MotherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->whereNotIn('Exe_Remarks', ['Called & Mailed', 'Ready To Paid'])
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Ready To Paid" counts
        $hourlyReadyToPaid = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Other Calls" counts
        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->whereNotIn('Exe_Remarks', ['Called & Mailed', 'Ready To Paid'])
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();


        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        $r10to11am = $hourlyReadyToPaid[10] ?? 0;
        $r11to12pm = $hourlyReadyToPaid[11] ?? 0;
        $r12to1pm  = $hourlyReadyToPaid[12] ?? 0;
        $r1to2pm   = $hourlyReadyToPaid[13] ?? 0;
        $r2to3pm   = $hourlyReadyToPaid[14] ?? 0;
        $r3to4pm   = $hourlyReadyToPaid[15] ?? 0;
        $r4to5pm   = $hourlyReadyToPaid[16] ?? 0;
        $r5to6pm   = $hourlyReadyToPaid[17] ?? 0;
        $r6to7pm   = $hourlyReadyToPaid[18] ?? 0;
        $r7to8pm   = $hourlyReadyToPaid[19] ?? 0;


        return view('reports.seniormonthly', compact(
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MreadyToPaidCalls',
            'MotherCalls',
            'selectedMonth',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm',
            'r10to11am',
            'r11to12pm',
            'r12to1pm',
            'r1to2pm',
            'r2to3pm',
            'r3to4pm',
            'r4to5pm',
            'r5to6pm',
            'r6to7pm',
            'r7to8pm'

        ));
    }


    public function alljuniorlist(Request $request)
    {
        // Fetch all users with role 'junior'
        $juniorUsers = User::where('role', 'junior')->get();

        // Pass users to the view
        return view('reports.alljuniorlist', compact('juniorUsers'));
    }

    public function allseniorlist(Request $request)
    {
        // Fetch all users with role 'senior'
        $seniorUsers = User::where('role', 'senior')->get();

        // Pass users to the view
        return view('reports.allseniorlist', compact('seniorUsers'));
    }

    public function allaccountantlist(Request $request)
    {
        // Fetch all users with role 'senior'
        $accountantUsers = User::where('role', 'accountant')->get();

        // Pass users to the view
        return view('reports.allaccountantlist', compact('accountantUsers'));
    }

    public function alltrainerlist(Request $request)
    {
        // Fetch all users with role 'trainer'
        $trainerUsers = User::where('role', 'trainer')->get();

        // Pass users to the view
        return view('reports.alltrainerlist', compact('trainerUsers'));
    }

    public function alljuniordaily(Request $request, $userId)
    {
        // Get the junior user
        $juniorUser = User::findOrFail($userId);
        $createdByKey = "{$juniorUser->id}|junior";

        // ================================
        // Main logic with LIKE filters
        // ================================

        // Total calls for this junior (including hierarchical keys)
        $totalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")->count();

        // Total "Called & Mailed" calls for this junior
        $calledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls for this junior
        $otherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Group data by hour of updated_at (for this junior)
        $hourlyCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Selected date (default today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by this junior and date
        $query = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate);

        // Selected date totals for this junior
        $StotalCalls = $query->count();

        $ScalledAndMailedCalls = (clone $query)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        $SotherCalls = (clone $query)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();



        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.alljuniordaily', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SotherCalls',
            'selectedDate',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }

    public function alltrainerdaily(Request $request, $userId)
    {
        // Get the trainer user
        $trainerUser = User::findOrFail($userId);
        $createdByKey = "{$trainerUser->id}|trainer";

        // ================================
        // Main logic with LIKE filters
        // ================================

        // Total calls for this trainer (including hierarchical keys)
        $totalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")->count();

        // Total "Called & Mailed" calls for this trainer
        $calledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls for this trainer
        $otherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Group data by hour of updated_at (for this trainer)
        $hourlyCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Selected date (default today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by this trainer and date
        $query = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate);

        // Selected date totals for this trainer
        $StotalCalls = $query->count();

        $ScalledAndMailedCalls = (clone $query)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        $SotherCalls = (clone $query)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();



        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.alljuniordaily', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SotherCalls',
            'selectedDate',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }


    public function allaccountantdaily(Request $request, $userId)
    {
        // Get the accountant user
        $juniorUser = User::findOrFail($userId);
        $createdByKey = "{$juniorUser->id}|junior";

        // ================================
        // Main logic with LIKE filters
        // ================================

        // Total calls for this junior (including hierarchical keys)
        $totalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")->count();

        // Total "Called & Mailed" calls for this junior
        $calledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls for this junior
        $otherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Group data by hour of updated_at (for this junior)
        $hourlyCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Selected date (default today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by this junior and date
        $query = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate);

        // Selected date totals for this junior
        $StotalCalls = $query->count();

        $ScalledAndMailedCalls = (clone $query)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        $SotherCalls = (clone $query)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();



        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.alljuniordaily', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SotherCalls',
            'selectedDate',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }

    public function allseniordaily(Request $request, $userId)
    {
        $createdByKey = "{$userId}|senior";

        // ================================
        // Main logic with LIKE filters
        // ================================

        // Total calls for this senior (including hierarchical keys)
        $totalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")->count();

        // Total "Called & Mailed" calls
        $calledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total "Ready To Paid" calls
        $readyToPaidCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where('Exe_Remarks', 'Ready To Paid')
            ->count();

        // Total other calls (excluding Called & Mailed)
        $otherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Selected date (default today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by this senior and date
        $query = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate);

        // Selected date totals
        $StotalCalls = $query->count();
        $ScalledAndMailedCalls = (clone $query)->where('Exe_Remarks', 'Called & Mailed')->count();
        $SreadyToPaidCalls = (clone $query)->where('Exe_Remarks', 'Ready To Paid')->count();
        $SotherCalls = (clone $query)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('Exe_Remarks', '<>', 'Called & Mailed')
                        ->where('Exe_Remarks', '<>', 'Ready To Paid');
                })
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();



        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Ready To Paid" counts
        $hourlyReadyToPaid = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise other calls
        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('Exe_Remarks', '<>', 'Called & Mailed')
                        ->where('Exe_Remarks', '<>', 'Ready To Paid');
                })
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();



        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $r10to11am = $hourlyReadyToPaid[10] ?? 0;
        $r11to12pm = $hourlyReadyToPaid[11] ?? 0;
        $r12to1pm  = $hourlyReadyToPaid[12] ?? 0;
        $r1to2pm   = $hourlyReadyToPaid[13] ?? 0;
        $r2to3pm   = $hourlyReadyToPaid[14] ?? 0;
        $r3to4pm   = $hourlyReadyToPaid[15] ?? 0;
        $r4to5pm   = $hourlyReadyToPaid[16] ?? 0;
        $r5to6pm   = $hourlyReadyToPaid[17] ?? 0;
        $r6to7pm   = $hourlyReadyToPaid[18] ?? 0;
        $r7to8pm   = $hourlyReadyToPaid[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.allseniordaily', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'readyToPaidCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SreadyToPaidCalls',
            'SotherCalls',
            'selectedDate',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'r10to11am',
            'r11to12pm',
            'r12to1pm',
            'r1to2pm',
            'r2to3pm',
            'r3to4pm',
            'r4to5pm',
            'r5to6pm',
            'r6to7pm',
            'r7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }



    public function alljuniormonthly(Request $request, $userId)
    {
        $juniorUser = User::findOrFail($userId);
        $createdByKey = "{$juniorUser->id}|junior";

        // Selected month (default current month in YYYY-MM)
        $selectedMonth = $request->input('selected_month', date('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);

        // Total calls for this junior in the selected month (including hierarchical keys)
        $MtotalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->count();

        // Total "Called & Mailed" calls
        $McalledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls (not "Called & Mailed")
        $MotherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Other Calls" counts
        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();


        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.alljuniormonthly', compact(
            'juniorUser',
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MotherCalls',
            'selectedMonth',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }

    public function allaccountantmonthly(Request $request, $userId)
    {
        $accountantUser = User::findOrFail($userId);
        $createdByKey = "{$accountantUser->id}|accountant";

        // Selected month (default current month in YYYY-MM)
        $selectedMonth = $request->input('selected_month', date('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);

        // Total calls for this accountant in the selected month (including hierarchical keys)
        $MtotalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->count();

        // Total "Called & Mailed" calls
        $McalledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls (not "Called & Mailed")
        $MotherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Other Calls" counts
        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();


        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.allaccountantmonthly', compact(
            'accountantUser',
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MotherCalls',
            'selectedMonth',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }

    public function alltrainermonthly(Request $request, $userId)
    {
        $trainerUser = User::findOrFail($userId);
        $createdByKey = "{$trainerUser->id}|trainer";

        // Selected month (default current month in YYYY-MM)
        $selectedMonth = $request->input('selected_month', date('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);

        // Total calls for this trainer in the selected month (including hierarchical keys)
        $MtotalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->count();

        // Total "Called & Mailed" calls
        $McalledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls (not "Called & Mailed")
        $MotherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Other Calls" counts
        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();


        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.alltrainermonthly', compact(
            'trainerUser',
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MotherCalls',
            'selectedMonth',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }

    public function allseniormonthly(Request $request, $userId)
    {
        $createdByKey = "{$userId}|senior";

        // Selected month (default current month in YYYY-MM)
        $selectedMonth = $request->input('selected_month', date('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);

        // Total calls for this senior in the selected month (including hierarchical keys)
        $MtotalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->count();

        // Total "Called & Mailed" calls
        $McalledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total "Ready To Paid" calls
        $MreadyToPaidCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->count();

        // Total other calls (not "Called & Mailed" or "Ready To Paid")
        $MotherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->whereNotIn('Exe_Remarks', ['Called & Mailed', 'Ready To Paid'])
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Ready To Paid" counts
        $hourlyReadyToPaid = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Other Calls" counts
        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->whereNotIn('Exe_Remarks', ['Called & Mailed', 'Ready To Paid'])
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();


        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        $r10to11am = $hourlyReadyToPaid[10] ?? 0;
        $r11to12pm = $hourlyReadyToPaid[11] ?? 0;
        $r12to1pm  = $hourlyReadyToPaid[12] ?? 0;
        $r1to2pm   = $hourlyReadyToPaid[13] ?? 0;
        $r2to3pm   = $hourlyReadyToPaid[14] ?? 0;
        $r3to4pm   = $hourlyReadyToPaid[15] ?? 0;
        $r4to5pm   = $hourlyReadyToPaid[16] ?? 0;
        $r5to6pm   = $hourlyReadyToPaid[17] ?? 0;
        $r6to7pm   = $hourlyReadyToPaid[18] ?? 0;
        $r7to8pm   = $hourlyReadyToPaid[19] ?? 0;


        return view('reports.allseniormonthly', compact(
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MreadyToPaidCalls',
            'MotherCalls',
            'selectedMonth',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm',
            'r10to11am',
            'r11to12pm',
            'r12to1pm',
            'r1to2pm',
            'r2to3pm',
            'r3to4pm',
            'r4to5pm',
            'r5to6pm',
            'r6to7pm',
            'r7to8pm'

        ));
    }



    public function junior(Request $request)
    {
        $user = Auth::user();
        $createdByKey = "{$user->id}|junior";

        // ================================
        // Main logic with LIKE filters
        // ================================

        // Total calls for this junior (including hierarchical keys)
        $totalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")->count();

        // Total "Called & Mailed" calls for this junior
        $calledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls for this junior
        $otherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Group data by hour of updated_at (for this junior)
        $hourlyCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Selected date (default today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by this junior and date
        $query = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate);

        // Selected date totals for this junior
        $StotalCalls = $query->count();

        $ScalledAndMailedCalls = (clone $query)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        $SotherCalls = (clone $query)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereDate('updated_at', $selectedDate)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();



        // Initialize hour blocks (8 PM - 6 AM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.junior', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SotherCalls',
            'selectedDate',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'

        ));
    }



    public function juniormonthly(Request $request)
    {
        $user = Auth::user();
        $createdByKey = "{$user->id}|junior";

        // Selected month (default current month in YYYY-MM)
        $selectedMonth = $request->input('selected_month', date('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);

        // Total calls for this junior in the selected month (including hierarchical keys)
        $MtotalCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->count();

        // Total "Called & Mailed" calls
        $McalledAndMailedCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls (not "Called & Mailed")
        $MotherCalls = GoogleSheetData::where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->where('Exe_Remarks', '<>', 'Called & Mailed')
                    ->orWhereNull('Exe_Remarks');
            })
            ->count();


        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Other Calls" counts
        $hourlyOtherCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', 'like', "{$createdByKey}%")
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where(function ($q) {
                $q->whereNull('Exe_Remarks')
                    ->orWhere('Exe_Remarks', '<>', 'Called & Mailed');
            })
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();


        // Initialize hour blocks (10 AM - 8 PM)
        $t10to11am = $hourlyCalledMailed[10] ?? 0;
        $t11to12pm = $hourlyCalledMailed[11] ?? 0;
        $t12to1pm  = $hourlyCalledMailed[12] ?? 0;
        $t1to2pm   = $hourlyCalledMailed[13] ?? 0;
        $t2to3pm   = $hourlyCalledMailed[14] ?? 0;
        $t3to4pm   = $hourlyCalledMailed[15] ?? 0;
        $t4to5pm   = $hourlyCalledMailed[16] ?? 0;
        $t5to6pm   = $hourlyCalledMailed[17] ?? 0;
        $t6to7pm   = $hourlyCalledMailed[18] ?? 0;
        $t7to8pm   = $hourlyCalledMailed[19] ?? 0;

        $o10to11am = $hourlyOtherCalls[10] ?? 0;
        $o11to12pm = $hourlyOtherCalls[11] ?? 0;
        $o12to1pm  = $hourlyOtherCalls[12] ?? 0;
        $o1to2pm   = $hourlyOtherCalls[13] ?? 0;
        $o2to3pm   = $hourlyOtherCalls[14] ?? 0;
        $o3to4pm   = $hourlyOtherCalls[15] ?? 0;
        $o4to5pm   = $hourlyOtherCalls[16] ?? 0;
        $o5to6pm   = $hourlyOtherCalls[17] ?? 0;
        $o6to7pm   = $hourlyOtherCalls[18] ?? 0;
        $o7to8pm   = $hourlyOtherCalls[19] ?? 0;

        return view('reports.juniormonthly', compact(
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MotherCalls',
            'selectedMonth',
            't10to11am',
            't11to12pm',
            't12to1pm',
            't1to2pm',
            't2to3pm',
            't3to4pm',
            't4to5pm',
            't5to6pm',
            't6to7pm',
            't7to8pm',
            'o10to11am',
            'o11to12pm',
            'o12to1pm',
            'o1to2pm',
            'o2to3pm',
            'o3to4pm',
            'o4to5pm',
            'o5to6pm',
            'o6to7pm',
            'o7to8pm'
        ));
    }
}
