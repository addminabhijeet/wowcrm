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

        // Dynamic label colors (can later be fetched from DB)
        $labelColors = [
            'login'  => '#007bff',
            'resume' => '#28a745',
            'pause'  => '#ffc107',
            'logout' => '#dc3545',
            'other'  => '#6c757d',
        ];

        $eventsData = $events->map(function ($event) use ($labelColors) {
            $label = strtolower($event->pause_type);
            return [
                'id'    => $event->id,
                'title' => ucfirst($event->pause_type),
                'start' => $event->event_time,
                'end'   => $event->event_time, // Adjust if duration exists
                'backgroundColor' => $labelColors[$label] ?? $labelColors['other'],
                'borderColor'     => $labelColors[$label] ?? $labelColors['other'],
                'extendedProps' => [
                    'status'            => $event->status,
                    'pause_type'        => $event->pause_type,
                    'remaining_seconds' => $event->remaining_seconds,
                    'label'             => $label,
                    'label_color'       => $labelColors[$label] ?? $labelColors['other'],
                ],
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
