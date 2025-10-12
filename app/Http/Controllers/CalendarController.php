<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UserTimerPause;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

        return view('calendar.junior', compact('view', 'date'));
    }

    // ✅ FullCalendar event JSON endpoint
    public function juniorEvents(Request $request)
    {
        $userId = Auth::id();

        $events = UserTimerPause::where('user_id', $userId)
            ->orderBy('event_time', 'asc')
            ->get();

        // Define color mapping for clarity
        $labelColors = [
            'start'  => '#007bff',
            'resume' => '#28a745',
            'pause'  => '#ffc107',
            'stop'   => '#dc3545',
            'other'  => '#6c757d'
        ];

        $eventsData = $events->map(function ($event) use ($labelColors) {
            $type = strtolower($event->pause_type ?? 'other');

            return [
                'id'    => $event->id,
                'title' => ucfirst($type),
                'start' => $event->event_time,
                'allDay' => false,
                'extendedProps' => [
                    'status'            => $event->status ?? 'N/A',
                    'pause_type'        => $type,
                    'remaining_seconds' => $event->remaining_seconds ?? 0,
                    'label_color'       => $labelColors[$type] ?? $labelColors['other'],
                ]
            ];
        });

        return response()->json($eventsData);
    }

    public function allJuniorlist(Request $request)
    {
        // Fetch all users with role 'junior'
        $juniorUsers = User::where('role', 'junior')->get();

        // Pass users to the view
        return view('calendar.alljuniorlist', compact('juniorUsers'));
    }

    public function alljuniorUser(Request $request, $user_id)
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

        $events = UserTimerPause::where('user_id', $user_id)
            ->whereBetween('event_time', [$start, $end])
            ->orderBy('event_time', 'asc')
            ->get();

        return view('calendar.junior', compact('events', 'view', 'date'));
    }


    public function getAllJuniorEvents(Request $request)
    {
        // Fetch all juniors
        $juniorUsers = User::where('role', 'junior')->get()->keyBy('id');

        // Fetch all UserTimerPause events for these junior users
        $events = UserTimerPause::whereIn('user_id', $juniorUsers->keys())
            ->orderBy('event_time', 'asc')
            ->get();

        // Dynamic colors based on pause_type or label
        $labelColors = [
            'login'  => '#007bff',
            'resume' => '#28a745',
            'pause'  => '#ffc107',
            'other'  => '#6c757d'
        ];

        $eventsData = $events->map(function ($event) use ($labelColors, $juniorUsers) {
            $userName = $juniorUsers[$event->user_id]->name ?? 'Junior User';
            return [
                'id' => $event->id,
                'title' => $userName . ': ' . ucfirst($event->pause_type),
                'start' => $event->event_time,
                'end'   => $event->event_time, // adjust if you have duration
                'extendedProps' => [
                    'user_id' => $event->user_id,
                    'user_name' => $userName,
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

        return view('calendar.senior', compact('view', 'date'));
    }

    public function getSeniorEvents(Request $request)
    {
        $userId = Auth::id();

        $events = UserTimerPause::where('user_id', $userId)
            ->orderBy('event_time', 'asc')
            ->get();

        // Define color mapping for clarity
        $labelColors = [
            'start'  => '#007bff',
            'resume' => '#28a745',
            'pause'  => '#ffc107',
            'stop'   => '#dc3545',
            'other'  => '#6c757d'
        ];

        $eventsData = $events->map(function ($event) use ($labelColors) {
            $type = strtolower($event->pause_type ?? 'other');

            return [
                'id'    => $event->id,
                'title' => ucfirst($type),
                'start' => $event->event_time,
                'allDay' => false,
                'extendedProps' => [
                    'status'            => $event->status ?? 'N/A',
                    'pause_type'        => $type,
                    'remaining_seconds' => $event->remaining_seconds ?? 0,
                    'label_color'       => $labelColors[$type] ?? $labelColors['other'],
                ]
            ];
        });

        return response()->json($eventsData);
    }
}
