<?php

namespace App\Http\Controllers;
use App\Models\GoogleSheetData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



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

    public function senior()
    {
        $today = now();
        $currentWeekStart = $today->copy()->startOfWeek();
        $currentWeekEnd = $today->copy()->endOfWeek();
        $currentMonth = $today->month;
        $currentYear = $today->year;

        // ===== Calls per Hour (Today & Monthly view) =====
        $callsPerHour = DB::table('google_sheet_data')
            ->selectRaw('HOUR(created_at) as call_hour, COUNT(*) as total_calls')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Exe Remarks\"')) = 'Called & Mailed'")
            ->whereDate('created_at', $today)
            ->groupBy('call_hour')
            ->orderBy('call_hour')
            ->get();

        $allHours = collect(range(0, 23));
        $callsPerHour = $allHours->map(function ($h) use ($callsPerHour) {
            $match = $callsPerHour->firstWhere('call_hour', $h);
            return ['call_hour' => $h, 'total_calls' => $match->total_calls ?? 0];
        });
        $hourLabels = $callsPerHour->map(fn($h) => ($h['call_hour'] + 1) . ' Hrs');

        // ===== Calls per Day (Weekly) =====
        $callsPerDayWeek = DB::table('google_sheet_data')
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total_calls')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Exe Remarks\"')) = 'Called & Mailed'")
            ->whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $daysThisWeek = [];
        for ($d = $currentWeekStart->copy(); $d <= $currentWeekEnd; $d->addDay()) {
            $daysThisWeek[] = $callsPerDayWeek[$d->toDateString()]->total_calls ?? 0;
        }
        $dayLabels = [];
        for ($d = $currentWeekStart->copy(); $d <= $currentWeekEnd; $d->addDay()) {
            $dayLabels[] = $d->format('D');
        }

        // ===== Calls per Month (Yearly) =====
        $callsPerMonth = DB::table('google_sheet_data')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_calls')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Exe Remarks\"')) = 'Called & Mailed'")
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total_calls')
            ->toArray();

        for ($i = 1; $i <= 12; $i++) {
            $callsPerMonth[$i - 1] = $callsPerMonth[$i - 1] ?? 0;
        }

        // ===== Total Calls Today =====
        $totalCallsToday = DB::table('google_sheet_data')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Exe Remarks\"')) = 'Called & Mailed'")
            ->whereDate('created_at', $today)
            ->count();

        return view('reports.senior', compact(
            'callsPerHour', 'hourLabels',
            'daysThisWeek', 'dayLabels',
            'callsPerMonth', 'totalCallsToday'
        ));
    }


public function junior()
{
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

    // Fill missing hours with 0 (optional)
    $allHours = range(0, 23);
    $chartData = [];
    foreach ($allHours as $h) {
        $chartData[] = [
            'x' => $h . ':00',
            'y' => $hourlyCalls[$h] ?? 0,
        ];
    }

    return view('reports.junior', compact(
        'totalCalls',
        'otherCalls',
        'calledAndMailedCalls',
        'chartData'
    ));
}



}