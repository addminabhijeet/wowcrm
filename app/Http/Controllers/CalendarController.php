<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get all days in month
        $dates = [];
        for ($day = $startOfMonth->day; $day <= $endOfMonth->day; $day++) {
            $dates[] = $startOfMonth->copy()->day($day);
        }

        // Get attendance for logged-in user
        $attendances = Attendance::where('user_id', Auth::id())
                        ->whereBetween('date', [$startOfMonth, $endOfMonth])
                        ->get()
                        ->keyBy(function($item) { return $item->date->format('Y-m-d'); });

        return view('calendar.admin', compact('dates', 'attendances', 'month', 'year'));
    }

    public function seniorUser($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get all days in month
        $dates = [];
        for ($day = $startOfMonth->day; $day <= $endOfMonth->day; $day++) {
            $dates[] = $startOfMonth->copy()->day($day);
        }

        // Get attendance for logged-in user
        $attendances = Attendance::where('user_id', Auth::id())
                        ->whereBetween('date', [$startOfMonth, $endOfMonth])
                        ->get()
                        ->keyBy(function($item) { return $item->date->format('Y-m-d'); });

        return view('calendar.senior', compact('dates', 'attendances', 'month', 'year'));
    }

    public function juniorUser($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get all days in month
        $dates = [];
        for ($day = $startOfMonth->day; $day <= $endOfMonth->day; $day++) {
            $dates[] = $startOfMonth->copy()->day($day);
        }

        // Get attendance for logged-in user
        $attendances = Attendance::where('user_id', Auth::id())
                        ->whereBetween('date', [$startOfMonth, $endOfMonth])
                        ->get()
                        ->keyBy(function($item) { return $item->date->format('Y-m-d'); });

        return view('calendar.senior', compact('dates', 'attendances', 'month', 'year'));
    }

    // Update status via AJAX
    public function updateStatus(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:working,holiday,present,absent'
        ]);

        $attendance = Attendance::updateOrCreate(
            ['user_id' => Auth::id(), 'date' => $request->date],
            ['status' => $request->status]
        );

        return response()->json(['success' => true, 'status' => $attendance->status]);
    }
}
