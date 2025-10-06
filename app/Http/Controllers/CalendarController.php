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
        $events = UserTimerPause::select('id', 'title', 'start_date', 'end_date', 'description', 'pause_type', 'status', 'label')
            ->get();

        // Define dynamic colors based on label type from DB or a mapping
        $labelColors = [
            'login' => '#007bff',   // blue
            'resume' => '#28a745',  // green
            'pause' => '#ffc107',   // yellow
            'other' => '#6c757d'    // gray
        ];

        $eventsData = $events->map(function ($event) use ($labelColors) {
            return [
                'id' => $event->id,
                'title' => $event->title ?? ucfirst($event->pause_type),
                'start' => $event->start_date,
                'end' => $event->end_date,
                'description' => $event->description,
                'label' => $event->label ?? 'Other',
                'label_color' => $labelColors[$event->label] ?? $labelColors['other']
            ];
        });

        return response()->json($eventsData);
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
