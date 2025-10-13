<?php

namespace App\Http\Controllers;

use App\Models\GoogleSheetData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class CallReportController extends Controller
{
    public function index()
    {
        // Base extracted data
        $calls = DB::table('google_sheet_data')
            ->select(
                'id',
                'sheet_row_number',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Date')) as call_date"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Name')) as candidate_name"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Email Address\"')) as email"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Phone Number\"')) as phone"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Location')) as location"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Relocation')) as relocation"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Graduation Date\"')) as graduation_date"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Immigration')) as immigration"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Course')) as course"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Amount')) as amount"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Qualification')) as qualification"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Exe Remarks\"')) as exe_remarks"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"1st Follow Up Remarks\"')) as followup_remarks"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Rating')) as rating"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.Comments')) as comments"),
                'created_at'
            )
            ->get();

        // Calls per hour
        $callsPerHour = DB::table('google_sheet_data')
            ->selectRaw('HOUR(created_at) as call_hour, COUNT(*) as total_calls')
            ->groupBy('call_hour')
            ->orderBy('call_hour')
            ->get();

        // Duplicate counts
        $dupByName = DB::table('google_sheet_data')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(data, "$.Name")) as candidate_name, COUNT(*) as cnt')
            ->groupBy('candidate_name')
            ->having('cnt', '>', 1)
            ->orderByDesc('cnt')
            ->get();

        $dupByEmail = DB::table('google_sheet_data')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(data, "$.\"Email Address\"")) as email, COUNT(*) as cnt')
            ->groupBy('email')
            ->having('cnt', '>', 1)
            ->orderByDesc('cnt')
            ->get();

        $dupByPhone = DB::table('google_sheet_data')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(data, "$.\"Phone Number\"")) as phone, COUNT(*) as cnt')
            ->groupBy('phone')
            ->having('cnt', '>', 1)
            ->orderByDesc('cnt')
            ->get();

        $locationDist = DB::table('google_sheet_data')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(data, "$.Location")) as location, COUNT(*) as cnt')
            ->groupBy('location')
            ->orderByDesc('cnt')
            ->get();

        // Follow-up remarks distribution
        $followUps = DB::table('google_sheet_data')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(data, "$.\"1st Follow Up Remarks\"")) as followup, COUNT(*) as cnt')
            ->groupBy('followup')
            ->orderByDesc('cnt')
            ->get();

        // Rating distribution
        $ratings = DB::table('google_sheet_data')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(data, "$.Rating")) as rating, COUNT(*) as cnt')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        return view('reports.admin', compact(
            'calls',
            'callsPerHour',
            'dupByName',
            'dupByEmail',
            'dupByPhone',
            'locationDist',
            'followUps',
            'ratings'
        ));
    }
    public function senior(Request $request)
    {
        $user = Auth::user();
        $createdByKey = "{$user->id}|senior";

        // Total calls for this senior
        $totalCalls = GoogleSheetData::where('created_by', $createdByKey)->count();

        // Total "Called & Mailed" calls for this senior
        $calledAndMailedCalls = GoogleSheetData::where('created_by', $createdByKey)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total "Called & Mailed" calls for this senior
        $ReadyToPaidCalls = GoogleSheetData::where('created_by', $createdByKey)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->count();

        // Total other calls for this senior
        $otherCalls = GoogleSheetData::where('created_by', $createdByKey)
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        // Group data by hour of updated_at (for this senior)
        $hourlyCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Selected date (default: today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by this senior and date
        $query = GoogleSheetData::where('created_by', $createdByKey)
            ->whereDate('updated_at', $selectedDate);

        // Selected date totals for this senior
        $StotalCalls = $query->count();

        $ScalledAndMailedCalls = (clone $query)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        $ReadyToPaidCalls = (clone $query)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->count();

        $SotherCalls = (clone $query)
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Called & Mailed" counts
        $hourlyReadyToPaid = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Initialize hour blocks (8 PM - 6 AM)
        $t8to9pm  = $hourlyCalledMailed[20] ?? 0;
        $t9to10pm = $hourlyCalledMailed[21] ?? 0;
        $t10to11pm = $hourlyCalledMailed[22] ?? 0;
        $t11to12pm = $hourlyCalledMailed[23] ?? 0;
        $t12to1am  = $hourlyCalledMailed[0] ?? 0;
        $t1to2am   = $hourlyCalledMailed[1] ?? 0;
        $t2to3am   = $hourlyCalledMailed[2] ?? 0;
        $t3to4am   = $hourlyCalledMailed[3] ?? 0;
        $t4to5am   = $hourlyCalledMailed[4] ?? 0;
        $t5to6am   = $hourlyCalledMailed[5] ?? 0;

        return view('reports.senior', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SotherCalls',
            'selectedDate',
            't8to9pm',
            't9to10pm',
            't10to11pm',
            't11to12pm',
            't12to1am',
            't1to2am',
            't2to3am',
            't3to4am',
            't4to5am',
            't5to6am'
        ));
    }

    public function seniormonthly(Request $request)
    {
        $user = Auth::user();
        $createdByKey = "{$user->id}|senior";

        // Selected month (default current month in YYYY-MM format)
        $selectedMonth = $request->input('selected_month', date('Y-m'));

        // Extract year and month for filtering
        [$year, $month] = explode('-', $selectedMonth);

        // Base query filtered by senior and month
        $query = GoogleSheetData::where('created_by', $createdByKey)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month);

        // Selected month totals
        $MtotalCalls = $query->count();

        $McalledAndMailedCalls = (clone $query)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        $MreadyToPaidCalls = (clone $query)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->count();

        $MotherCalls = (clone $query)
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hour-wise "Called & Mailed" counts
        $hourlyReadyToPaid = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Ready To Paid')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Initialize hour variables (20 = 8 PM, etc.)
        $t8to9pm  = $hourlyCalledMailed[20] ?? 0;
        $t9to10pm = $hourlyCalledMailed[21] ?? 0;
        $t10to11pm = $hourlyCalledMailed[22] ?? 0;
        $t11to12pm = $hourlyCalledMailed[23] ?? 0;
        $t12to1am  = $hourlyCalledMailed[0] ?? 0;
        $t1to2am   = $hourlyCalledMailed[1] ?? 0;
        $t2to3am   = $hourlyCalledMailed[2] ?? 0;
        $t3to4am   = $hourlyCalledMailed[3] ?? 0;
        $t4to5am   = $hourlyCalledMailed[4] ?? 0;
        $t5to6am   = $hourlyCalledMailed[5] ?? 0;

        return view('reports.seniormonthly', compact(
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MotherCalls',
            'selectedMonth',
            't8to9pm',
            't9to10pm',
            't10to11pm',
            't11to12pm',
            't12to1am',
            't1to2am',
            't2to3am',
            't3to4am',
            't4to5am',
            't5to6am'
        ));
    }

    public function alljuniorlist(Request $request)
    {
        // Fetch all users with role 'junior'
        $juniorUsers = User::where('role', 'junior')->get();

        // Pass users to the view
        return view('reports.alljuniorlist', compact('juniorUsers'));
    }

    public function alljuniordaily(Request $request, $userId)
    {
        // Get the junior user
        $juniorUser = User::findOrFail($userId);
        $createdByKey = "{$juniorUser->id}|junior";

        // Total calls
        $totalCalls = GoogleSheetData::count();

        // Total "Called & Mailed" calls
        $calledAndMailedCalls = GoogleSheetData::where('Exe_Remarks', 'Called & Mailed')->count();

        // Total other calls
        $otherCalls = GoogleSheetData::whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        // Group data by hour of updated_at
        $hourlyCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour') // key = hour, value = count
            ->toArray();

        // Selected date (default today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by date
        $query = GoogleSheetData::whereDate('updated_at', $selectedDate);

        // Selected date totals
        $StotalCalls = $query->count();
        $ScalledAndMailedCalls = $query->where('Exe_Remarks', 'Called & Mailed')->count();

        // Re-run query for other calls (since where modifies builder)
        $SotherCalls = GoogleSheetData::whereDate('updated_at', $selectedDate)
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Initialize hour variables
        $t8to9pm = $hourlyCalledMailed[20] ?? 0; // 20 = 8 PM
        $t9to10pm = $hourlyCalledMailed[21] ?? 0;
        $t10to11pm = $hourlyCalledMailed[22] ?? 0;
        $t11to12pm = $hourlyCalledMailed[23] ?? 0;
        $t12to1am = $hourlyCalledMailed[0] ?? 0;
        $t1to2am = $hourlyCalledMailed[1] ?? 0;
        $t2to3am = $hourlyCalledMailed[2] ?? 0;
        $t3to4am = $hourlyCalledMailed[3] ?? 0;
        $t4to5am = $hourlyCalledMailed[4] ?? 0;
        $t5to6am = $hourlyCalledMailed[5] ?? 0;

        return view('reports.alljuniordaily', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SotherCalls',
            'selectedDate',
            't8to9pm',
            't9to10pm',
            't10to11pm',
            't11to12pm',
            't12to1am',
            't1to2am',
            't2to3am',
            't3to4am',
            't4to5am',
            't5to6am'
        ));
    }


    public function alljuniormonthly(Request $request, $userId)
    {
        $juniorUser = User::findOrFail($userId);

        // Selected month (default current month)
        $selectedMonth = $request->input('selected_month', date('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);

        // Base query filtered by junior user and month
        $createdByKey = "{$juniorUser->id}|junior"; // adjust if your created_by format differs
        $query = GoogleSheetData::where('created_by', $createdByKey)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month);

        $MtotalCalls = $query->count();
        $McalledAndMailedCalls = (clone $query)->where('Exe_Remarks', 'Called & Mailed')->count();
        $MotherCalls = (clone $query)
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Map hours (same as your seniormonthly)
        $t8to9pm  = $hourlyCalledMailed[20] ?? 0;
        $t9to10pm = $hourlyCalledMailed[21] ?? 0;
        $t10to11pm = $hourlyCalledMailed[22] ?? 0;
        $t11to12pm = $hourlyCalledMailed[23] ?? 0;
        $t12to1am  = $hourlyCalledMailed[0] ?? 0;
        $t1to2am   = $hourlyCalledMailed[1] ?? 0;
        $t2to3am   = $hourlyCalledMailed[2] ?? 0;
        $t3to4am   = $hourlyCalledMailed[3] ?? 0;
        $t4to5am   = $hourlyCalledMailed[4] ?? 0;
        $t5to6am   = $hourlyCalledMailed[5] ?? 0;

        return view('reports.seniormonthly', compact(
            'juniorUser',
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MotherCalls',
            'selectedMonth',
            't8to9pm',
            't9to10pm',
            't10to11pm',
            't11to12pm',
            't12to1am',
            't1to2am',
            't2to3am',
            't3to4am',
            't4to5am',
            't5to6am'
        ));
    }


    public function junior(Request $request)
    {
        $user = Auth::user();
        $createdByKey = "{$user->id}|junior";

        // Total calls for this junior
        $totalCalls = GoogleSheetData::where('created_by', $createdByKey)->count();

        // Total "Called & Mailed" calls for this junior
        $calledAndMailedCalls = GoogleSheetData::where('created_by', $createdByKey)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        // Total other calls for this junior
        $otherCalls = GoogleSheetData::where('created_by', $createdByKey)
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        // Group data by hour of updated_at (for this junior)
        $hourlyCalls = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Selected date (default today)
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Base query filtered by this junior and date
        $query = GoogleSheetData::where('created_by', $createdByKey)
            ->whereDate('updated_at', $selectedDate);

        // Selected date totals for this junior
        $StotalCalls = $query->count();

        $ScalledAndMailedCalls = (clone $query)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->count();

        $SotherCalls = (clone $query)
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->whereDate('updated_at', $selectedDate)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Initialize hour blocks (8 PM - 6 AM)
        $t8to9pm  = $hourlyCalledMailed[20] ?? 0;
        $t9to10pm = $hourlyCalledMailed[21] ?? 0;
        $t10to11pm = $hourlyCalledMailed[22] ?? 0;
        $t11to12pm = $hourlyCalledMailed[23] ?? 0;
        $t12to1am  = $hourlyCalledMailed[0] ?? 0;
        $t1to2am   = $hourlyCalledMailed[1] ?? 0;
        $t2to3am   = $hourlyCalledMailed[2] ?? 0;
        $t3to4am   = $hourlyCalledMailed[3] ?? 0;
        $t4to5am   = $hourlyCalledMailed[4] ?? 0;
        $t5to6am   = $hourlyCalledMailed[5] ?? 0;

        return view('reports.junior', compact(
            'totalCalls',
            'calledAndMailedCalls',
            'otherCalls',
            'StotalCalls',
            'ScalledAndMailedCalls',
            'SotherCalls',
            'selectedDate',
            't8to9pm',
            't9to10pm',
            't10to11pm',
            't11to12pm',
            't12to1am',
            't1to2am',
            't2to3am',
            't3to4am',
            't4to5am',
            't5to6am'
        ));
    }


    public function juniormonthly(Request $request)
    {
        $user = Auth::user();
        $createdByKey = "{$user->id}|junior";

        // Selected month (default current month in YYYY-MM format)
        $selectedMonth = $request->input('selected_month', date('Y-m'));

        // Extract year and month for filtering
        [$year, $month] = explode('-', $selectedMonth);

        // Base query filtered by selected month and junior
        $query = GoogleSheetData::where('created_by', $createdByKey)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month);

        // Selected month totals
        $MtotalCalls = $query->count();
        $McalledAndMailedCalls = (clone $query)->where('Exe_Remarks', 'Called & Mailed')->count();

        // Other calls (excluding Called & Mailed)
        $MotherCalls = GoogleSheetData::where('created_by', $createdByKey)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
            ->count();

        // Hour-wise "Called & Mailed" counts
        $hourlyCalledMailed = GoogleSheetData::selectRaw('HOUR(updated_at) as hour, COUNT(*) as count')
            ->where('created_by', $createdByKey)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->where('Exe_Remarks', 'Called & Mailed')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Initialize hour variables (20 = 8 PM, etc.)
        $t8to9pm = $hourlyCalledMailed[20] ?? 0;
        $t9to10pm = $hourlyCalledMailed[21] ?? 0;
        $t10to11pm = $hourlyCalledMailed[22] ?? 0;
        $t11to12pm = $hourlyCalledMailed[23] ?? 0;
        $t12to1am = $hourlyCalledMailed[0] ?? 0;
        $t1to2am = $hourlyCalledMailed[1] ?? 0;
        $t2to3am = $hourlyCalledMailed[2] ?? 0;
        $t3to4am = $hourlyCalledMailed[3] ?? 0;
        $t4to5am = $hourlyCalledMailed[4] ?? 0;
        $t5to6am = $hourlyCalledMailed[5] ?? 0;

        return view('reports.juniormonthly', compact(
            'MtotalCalls',
            'McalledAndMailedCalls',
            'MotherCalls',
            'selectedMonth',
            't8to9pm',
            't9to10pm',
            't10to11pm',
            't11to12pm',
            't12to1am',
            't1to2am',
            't2to3am',
            't3to4am',
            't4to5am',
            't5to6am'
        ));
    }
}
