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
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
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
        $SotherCalls = (clone $query)->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
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
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
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
            ->whereNotNull('Exe_Remarks')
            ->whereNotIn('Exe_Remarks', ['Called & Mailed', 'Ready To Paid'])
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
            ->whereNotNull('Exe_Remarks')
            ->whereNotIn('Exe_Remarks', ['Called & Mailed', 'Ready To Paid'])
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Initialize hour blocks (10 AM - 8 PM) for all categories
        $hours = range(10, 19); // 10 to 19 => 10AM-8PM
        $tCalled = [];
        $tReady = [];
        $tOther  = [];

        foreach ($hours as $h) {
            $tCalled[$h] = $hourlyCalledMailed[$h] ?? 0;
            $tReady[$h]  = $hourlyReadyToPaid[$h] ?? 0;
            $tOther[$h]  = $hourlyOtherCalls[$h] ?? 0;
        }

        return view('reports.seniormonthly', array_merge(
            [
                'MtotalCalls',
                'McalledAndMailedCalls',
                'MreadyToPaidCalls',
                'MotherCalls',
                'selectedMonth'
            ],
            // Flatten hour variables to individual names for view
            [
                't10to11am' => $tCalled[10],
                't11to12pm' => $tCalled[11],
                't12to1pm'  => $tCalled[12],
                't1to2pm'   => $tCalled[13],
                't2to3pm'   => $tCalled[14],
                't3to4pm'   => $tCalled[15],
                't4to5pm'   => $tCalled[16],
                't5to6pm'   => $tCalled[17],
                't6to7pm'   => $tCalled[18],
                't7to8pm'   => $tCalled[19],

                'r10to11am' => $tReady[10],
                'r11to12pm' => $tReady[11],
                'r12to1pm'  => $tReady[12],
                'r1to2pm'   => $tReady[13],
                'r2to3pm'   => $tReady[14],
                'r3to4pm'   => $tReady[15],
                'r4to5pm'   => $tReady[16],
                'r5to6pm'   => $tReady[17],
                'r6to7pm'   => $tReady[18],
                'r7to8pm'   => $tReady[19],

                'o10to11am' => $tOther[10],
                'o11to12pm' => $tOther[11],
                'o12to1pm'  => $tOther[12],
                'o1to2pm'   => $tOther[13],
                'o2to3pm'   => $tOther[14],
                'o3to4pm'   => $tOther[15],
                'o4to5pm'   => $tOther[16],
                'o5to6pm'   => $tOther[17],
                'o6to7pm'   => $tOther[18],
                'o7to8pm'   => $tOther[19]
            ]
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
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
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
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
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
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
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
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
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
            ->whereNotNull('Exe_Remarks')
            ->where('Exe_Remarks', '<>', 'Called & Mailed')
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
