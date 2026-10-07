@extends('layout.layout')

@php
$title = 'Users -> Login / Logout Report';
$role = auth()->user()->role ?? '';
$subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'Senior');
$script = '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
$totalLogin  = collect($hourly)->sum('login');
$totalLogout = collect($hourly)->sum('logout');
@endphp

@section('content')

<div class="row gy-4">
    {{-- Filters --}}
    <div class="col-12">
        <div class="card p-0">
            <div class="card-body p-24">
                <form method="GET" action="{{ route('senior.login') }}" class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-sm">Date</label>
                        <input type="date" name="date" value="{{ $date }}" class="form-control" max="{{ now()->toDateString() }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-sm">From Hour</label>
                        <select name="from_hour" class="form-select">
                            @for($h = 0; $h < 24; $h++)
                                <option value="{{ $h }}" @selected($fromHour == $h)>{{ sprintf('%02d:00', $h) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-sm">To Hour</label>
                        <select name="to_hour" class="form-select">
                            @for($h = 0; $h < 24; $h++)
                                <option value="{{ $h }}" @selected($toHour == $h)>{{ sprintf('%02d:59', $h) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-sm">User</label>
                        <select name="user_id" class="form-select">
                            <option value="">All users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" @selected($userId == $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-semibold text-sm">Event</label>
                        <select name="type" class="form-select">
                            <option value="all" @selected($type === 'all')>All</option>
                            <option value="login" @selected($type === 'login')>Login</option>
                            <option value="logout" @selected($type === 'logout')>Logout</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-600 flex-grow-1">Filter</button>
                        <a href="{{ route('senior.login') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Hourly summary --}}
    <div class="col-12">
        <div class="card p-0">
            <div class="card-header py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Hourly Summary &mdash; {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h6>
                <div class="text-sm">
                    <span class="text-success-main fw-semibold">{{ $totalLogin }} logins</span>
                    &nbsp;|&nbsp;
                    <span class="text-danger-main fw-semibold">{{ $totalLogout }} logouts</span>
                </div>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0 align-middle text-center">
                        <thead>
                            <tr>
                                <th class="text-start">Hour</th>
                                @foreach($hourly as $h => $c)
                                    <th>{{ sprintf('%02d:00', $h) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start fw-medium">Logins</td>
                                @foreach($hourly as $c)
                                    <td class="{{ $c['login'] ? 'text-success-main fw-semibold' : 'text-secondary-light' }}">{{ $c['login'] ?: '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="text-start fw-medium">Logouts</td>
                                @foreach($hourly as $c)
                                    <td class="{{ $c['logout'] ? 'text-danger-main fw-semibold' : 'text-secondary-light' }}">{{ $c['logout'] ?: '-' }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Event detail --}}
    <div class="col-12">
        <div class="card p-0">
            <div class="card-header py-16 px-24 border-bottom">
                <h6 class="mb-0">Login / Logout Activity ({{ $events->count() }})</h6>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Event</th>
                                <th>Source</th>
                                <th>IP Address</th>
                                <th class="text-center">Login Mail</th>
                                <th>Hour Slot</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $i => $e)
                                @php $u = $userMap[$e['user_id']] ?? null; @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <h6 class="text-md mb-0 fw-medium">{{ $u->name ?? 'Unknown' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $u->email ?? '' }}</span>
                                    </td>
                                    <td class="text-center">{{ $u ? ucfirst($u->role) : '-' }}</td>
                                    <td class="text-center">
                                        @if($e['type'] === 'Login')
                                            <span class="bg-success-focus text-success-main px-20 py-4 rounded-pill fw-medium text-sm">Login</span>
                                        @else
                                            <span class="bg-danger-focus text-danger-main px-20 py-4 rounded-pill fw-medium text-sm">Logout</span>
                                        @endif
                                    </td>
                                    <td>{{ $e['detail'] }}</td>
                                    <td>{{ $e['ip'] ?? '-' }}</td>
                                    <td class="text-center">
                                        @php $m = $e['mail'] ?? null; @endphp
                                        @if ($m && $m['status'] === 'sent')
                                            <span class="bg-success-focus text-success-main px-16 py-4 rounded-pill fw-medium text-sm" title="{{ $m['message'] }}">Sent</span>
                                        @elseif ($m && $m['status'] === 'failed')
                                            <span class="bg-danger-focus text-danger-main px-16 py-4 rounded-pill fw-medium text-sm" title="{{ $m['message'] }}">Failed</span>
                                            <div class="text-xs text-danger-main mt-4">{{ \Illuminate\Support\Str::limit($m['message'], 60) }}</div>
                                        @elseif ($m && $m['status'] === 'skipped')
                                            <span class="bg-warning-focus text-warning-main px-16 py-4 rounded-pill fw-medium text-sm" title="{{ $m['message'] }}">Not sent</span>
                                            <div class="text-xs text-secondary-light mt-4">{{ $m['message'] }}</div>
                                        @elseif ($m && $m['status'] === 'na')
                                            <span class="text-secondary-light text-sm" title="{{ $m['message'] }}">N/A (admin)</span>
                                        @else
                                            <span class="text-secondary-light">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $e['time']->format('H:00') }} - {{ $e['time']->format('H:59') }}</td>
                                    <td>{{ $e['time']->format('d M Y, h:i:s A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-secondary-light py-24">No login/logout activity found for the selected filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
