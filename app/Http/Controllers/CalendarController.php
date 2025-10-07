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
        $view = $request->input('view', 'month'); // day, week, month
        $date = $request->input('date', now());

        $start = $end = Carbon::parse($date);

        switch ($view) {
            case 'day':
                $start = $start->startOfDay();
                $end = $end->endOfDay();
                break;
            case 'week':
                $start = $start->startOfWeek();
                $end = $end->endOfWeek();
                break;
            default: // month
                $start = $start->startOfMonth();
                $end = $end->endOfMonth();
                break;
        }

        $events = UserTimerPause::where('user_id', Auth::id())
            ->whereBetween('event_time', [$start, $end])
            ->orderBy('event_time', 'asc')
            ->get();

        return view('calendar.junior', compact('events', 'view', 'date'));
    }

    public function getEvents(Request $request)
    {
        $events = UserTimerPause::where('user_id', Auth::id())
            ->orderBy('event_time', 'asc')
            ->get();

        // Dynamic colors based on pause_type or label
        $labelColors = [
            'login'  => '#007bff',
            'resume' => '#28a745',
            'pause'  => '#ffc107',
            'other'  => '#6c757d'
        ];

        $eventsData = $events->map(function ($event) use ($labelColors) {
            return [
                'id' => $event->id,
                'title' => ucfirst($event->pause_type),
                'start' => $event->event_time,
                'end'   => $event->event_time, // you can adjust if you have duration
                'extendedProps' => [
                    'status'  => $event->status,
                    'pause_type' => $event->pause_type,
                    'remaining_seconds' => $event->remaining_seconds,
                    'label' => $event->pause_type,
                    'label_color' => $labelColors[$event->pause_type] ?? $labelColors['other'],
                ]
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

    public function seniorUser(Request $request)
    {
        $view = $request->input('view', 'month'); // day, week, month
        $date = $request->input('date', now());

        $start = $end = Carbon::parse($date);

        switch ($view) {
            case 'day':
                $start = $start->startOfDay();
                $end = $end->endOfDay();
                break;
            case 'week':
                $start = $start->startOfWeek();
                $end = $end->endOfWeek();
                break;
            default: // month
                $start = $start->startOfMonth();
                $end = $end->endOfMonth();
                break;
        }

        // Fetch attendance for the logged-in user within the selected range
        $attendances = Attendance::where('user_id', Auth::id())
            ->whereBetween('date', [$start, $end])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Optional: Build array of all dates in range (useful for calendar)
        $dates = [];
        $period = Carbon::parse($start)->daysUntil($end);
        foreach ($period as $day) {
            $dates[] = $day->copy();
        }

        return view('calendar.senior', compact('dates', 'attendances', 'view', 'date'));
    }

    public function getSeniorEvents(Request $request)
    {
        $userId = Auth::id();

        // Fetch all attendance records for the user
        $attendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'asc')
            ->get();

        // Define dynamic colors based on attendance status
        $statusColors = [
            'present'    => '#28a745', // green
            'absent'     => '#dc3545', // red
            'late'       => '#ffc107', // yellow
            'holiday'    => '#17a2b8', // blue
            'work_from_home' => '#6f42c1', // purple
            'other'      => '#6c757d', // gray
        ];

        $eventsData = $attendances->map(function ($attendance) use ($statusColors) {
            $status = $attendance->status ?? 'other';
            return [
                'id' => $attendance->id,
                'title' => ucfirst(str_replace('_', ' ', $status)),
                'start' => $attendance->date,
                'end'   => $attendance->date, // same-day events
                'extendedProps' => [
                    'status' => $status,
                    'label_color' => $statusColors[$status] ?? $statusColors['other'],
                    'remarks' => $attendance->remarks ?? '',
                ],
            ];
        });

        return response()->json($eventsData);
    }
}
