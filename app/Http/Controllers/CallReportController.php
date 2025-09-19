<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('reports.senior', compact(
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

    public function junior()
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

        return view('reports.junior', compact(
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
}
