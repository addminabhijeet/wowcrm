<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UserTimerPause;
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
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        return view('calendar.admin', compact('dates', 'attendances', 'month', 'year'));
    }

    public function juniorUser(Request $request)
    {
        // Get date range from request (day, week, month)
        $view = $request->input('view', 'month'); // day, week, month
        $date = $request->input('date', now());   // reference date

        $start = null;
        $end = null;

        if ($view === 'day') {
            $start = Carbon::parse($date)->startOfDay();
            $end = Carbon::parse($date)->endOfDay();
        } elseif ($view === 'week') {
            $start = Carbon::parse($date)->startOfWeek();
            $end = Carbon::parse($date)->endOfWeek();
        } else { // month
            $start = Carbon::parse($date)->startOfMonth();
            $end = Carbon::parse($date)->endOfMonth();
        }

        // Fetch events within the date range
        $events = UserTimerPause::where('user_id', Auth::id()) // <-- filter by logged-in user
            ->whereBetween('event_time', [$start, $end])
            ->orderBy('event_time', 'asc')
            ->get();

        return view('calendar.junior', compact('events', 'view', 'date'));
    }

    public function getEvents(Request $request)
    {
        $start = $request->input('start'); // ISO string from FullCalendar
        $end   = $request->input('end');

        // Convert to Carbon
        $startDate = Carbon::parse($start);
        $endDate   = Carbon::parse($end);

        // Fetch events only for logged-in user
        $events = UserTimerPause::where('user_id', Auth::id())
            ->whereBetween('event_time', [$startDate, $endDate])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->pause_type,
                    'start' => $event->event_time, // or $event->start_date if using start/end columns
                    'end' => $event->end_date ?? $event->event_time,
                    'description' => $event->remaining_seconds,
                    'elapsed_seconds' => $event->elapsed_seconds,
                    'status' => $event->status,
                    'color' => $event->label_color ?? '#378006', // FullCalendar color
                ];
            });

        return response()->json($events);
    }


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
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        return view('calendar.senior', compact('dates', 'attendances', 'month', 'year'));
    }
}
