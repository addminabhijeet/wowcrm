@extends('layout.layout')
@php
$title = 'Target Analytics Dashboard';
$role = auth()->user()->role ?? '';
if($role === 'admin'){
$subTitle = 'Super Admin';
} elseif ($role === 'operation') {
$subTitle = 'Operation Manager';
} else{
$subTitle = $role;
}
@endphp

@section('content')

<div class="row gy-4">
    <!-- Users Analytics Table -->
    <div class="col-12">
        <div class="card radius-12 p-0 border-0">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-lg fw-semibold mb-0">Target Analysis - All Users</h6>
                <select id="yearSelect" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                    @for($year = $currentYear - 1; $year <= $currentYear + 1; $year++)
                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                        {{ $year }}
                        </option>
                        @endfor
                </select>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table table-hover mb-0" id="analyticsTable">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Target Given</th>
                                <th class="text-center">Target Achieved</th>
                                <th class="text-center">Variance</th>
                                <th class="text-center">Achievement %</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            @php
                            // Use pre-calculated summary from controller
                            $summary = $usersSummary[$user->id] ?? [];
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-primary-100 text-primary-600">
                                        {{ $user->role === 'junior' ? 'IT Recruiter' : 'IT Senior Recruiter' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-100 text-primary-600">
                                        ${{ number_format($summary['total_given']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-100 text-success-600">
                                        ${{ number_format($summary['total_achieved']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $summary['variance'] >= 0 ? 'bg-info-100 text-info-600' : 'bg-danger-100 text-danger-600' }}">
                                        {{ $summary['variance'] >= 0 ? '+' : '' }}${{ number_format($summary['variance']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div class="progress" style="width: 80px; height: 6px;">
                                            <div class="progress-bar {{ $summary['achievement_percent'] >= 100 ? 'bg-success' : ($summary['achievement_percent'] >= 75 ? 'bg-info' : ($summary['achievement_percent'] >= 50 ? 'bg-warning' : 'bg-danger')) }}" style="--progress-width: {{ min($summary['achievement_percent'], 100) }}%; width: var(--progress-width);"></div>
                                        </div>
                                        <small class="fw-semibold">{{ $summary['achievement_percent'] }}%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                    $statusMap = [
                                    'achieved' => ['icon' => 'ep:check-bold', 'badge' => 'bg-success-100 text-success-600', 'text' => '✓ Achieved'],
                                    'good' => ['icon' => 'ep:circle-check', 'badge' => 'bg-info-100 text-info-600', 'text' => 'Good'],
                                    'partial' => ['icon' => 'ep:circle-close', 'badge' => 'bg-warning-100 text-warning-600', 'text' => 'Partial'],
                                    'below' => ['icon' => 'ep:close-bold', 'badge' => 'bg-danger-100 text-danger-600', 'text' => 'Below'],
                                    'no_target' => ['icon' => 'ep:remove-filled', 'badge' => 'bg-secondary-100 text-secondary-600', 'text' => 'No Target']
                                    ];
                                    $status = $statusMap[$summary['status']] ?? $statusMap['no_target'];
                                    @endphp
                                    <span class="badge {{ $status['badge'] }}">
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('target-analytics.user-analytics', $user->id) }}" class="btn btn-sm btn-primary px-3">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-24">No users found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('yearSelect').addEventListener('change', function() {
            const year = this.value;
            window.location.href = `{{ route('target-analytics.dashboard') }}?year=${year}`;
        });
    </script>

    @endsection