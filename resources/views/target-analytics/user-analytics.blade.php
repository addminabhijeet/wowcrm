@extends('layout.layout')
@php
$title = 'Target Analytics - ' . $user->name;
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
    <!-- Summary Cards -->
    <div class="col-12">
        <div class="row gy-3">
            <!-- Total Target Given -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 20px; color: #0d47a1; transition: all 0.3s ease; cursor: pointer;"
                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.05)';">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <p class="mb-1 fw-semibold" style="font-size: 15px; opacity: 0.8;">Target Given</p>
                            <h3 class="mb-0 fw-bold" style="font-size: 18px !important;">${{ number_format($summary['total_given']) }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 70px; height: 70px; background-color: rgba(13,71,161,0.1); border-radius: 50%;">
                            <iconify-icon icon="mdi:bullseye-arrow" style="font-size: 34px; color: #0d47a1;"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Target Achieved -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 20px; color: #2e7d32; transition: all 0.3s ease; cursor: pointer;"
                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.05)';">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <p class="mb-1 fw-semibold" style="font-size: 15px; opacity: 0.8;">Target Achieved</p>
                            <h3 class="mb-0 fw-bold" style="font-size: 18px !important;">${{ number_format($summary['total_achieved']) }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 70px; height: 70px; background-color: rgba(46,125,50,0.1); border-radius: 50%;">
                            <iconify-icon icon="fa-solid:trophy" style="font-size: 34px; color: #2e7d32;"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Target Yet to Achieve -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #fff3e0, #ffe0b2); border-radius: 20px; color: #ef6c00; transition: all 0.3s ease; cursor: pointer;"
                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.05)';">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <p class="mb-1 fw-semibold" style="font-size: 15px; opacity: 0.8;">Yet to Achieve</p>
                            <h3 class="mb-0 fw-bold" style="font-size: 18px !important;">${{ number_format(max(0, $summary['total_given'] - $summary['total_achieved'])) }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 70px; height: 70px; background-color: rgba(239,108,0,0.1); border-radius: 50%;">
                            <iconify-icon icon="mdi:progress-clock" style="font-size: 34px; color: #ef6c00;"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievement % -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #f3e5f5, #e1bee7); border-radius: 20px; color: #6a1b9a; transition: all 0.3s ease; cursor: pointer;"
                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.05)';">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <p class="mb-1 fw-semibold" style="font-size: 15px; opacity: 0.8;">Achievement %</p>
                            <h3 class="mb-0 fw-bold" style="font-size: 18px !important;">{{ $summary['achievement_percent'] }}%</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 70px; height: 70px; background-color: rgba(106,27,154,0.1); border-radius: 50%;">
                            <iconify-icon icon="mdi:chart-pie" style="font-size: 34px; color: #6a1b9a;"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Info & Year Selector -->
    <div class="col-12">
        <div class="card radius-12 p-0 border-0">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                <div>
                    <h6 class="text-lg fw-semibold mb-0">{{ $user->name }}</h6>
                    <small class="text-secondary-light">{{ $user->email }} • {{ $user->role === 'junior' ? 'IT Recruiter' : 'IT Senior Recruiter' }}</small>
                </div>
                <div class="d-flex gap-3">
                    <select id="yearSelect" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                        @for($year = $currentYear - 2; $year <= $currentYear + 1; $year++)
                            <option value="{{ $year }}" {{ $year == $yearParam ? 'selected' : '' }}>
                            {{ $year }}
                            </option>
                            @endfor
                    </select>
                    <a href="{{ route('target-analytics.dashboard') }}" class="btn btn-sm btn-outline-primary">
                        Back to All Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown Table -->
    <div class="col-12">
        <div class="card radius-12 p-0 border-0">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="text-lg fw-semibold mb-0">Monthly Target Analysis - {{ $yearParam }}</h6>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-center">Target Given</th>
                                <th class="text-center">Target Achieved</th>
                                <th class="text-center">Variance</th>
                                <th class="text-center">Achievement %</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyData as $month => $data)
                            <tr>
                                <td class="fw-semibold">{{ $data['month_name'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary-100 text-primary-600">
                                        ${{ number_format($data['target_given']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-100 text-success-600">
                                        ${{ number_format($data['target_achieved']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $data['variance'] >= 0 ? 'bg-info-100 text-info-600' : 'bg-danger-100 text-danger-600' }}">
                                        {{ $data['variance'] >= 0 ? '+' : '' }}${{ number_format($data['variance']) }}
                                        <br>
                                        <small>({{ $data['variance_percent'] }}%)</small>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div class="progress" style="width: 100px; height: 6px;">
                                            <div class="progress-bar {{ $data['achievement_percent'] >= 100 ? 'bg-success' : ($data['achievement_percent'] >= 75 ? 'bg-info' : ($data['achievement_percent'] >= 50 ? 'bg-warning' : 'bg-danger')) }}"
                                                style="--progress-width: {{ min($data['achievement_percent'], 100) }}%; width: var(--progress-width);"></div>
                                        </div>
                                        <small class="fw-semibold">{{ $data['achievement_percent'] }}%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                    $statusBadge = [
                                    'achieved' => 'bg-success-100 text-success-600',
                                    'good' => 'bg-info-100 text-info-600',
                                    'partial' => 'bg-warning-100 text-warning-600',
                                    'below' => 'bg-danger-100 text-danger-600',
                                    'no_target' => 'bg-secondary-100 text-secondary-600'
                                    ];
                                    $statusText = [
                                    'achieved' => '✓ Achieved',
                                    'good' => '→ Good',
                                    'partial' => '◐ Partial',
                                    'below' => '✗ Below',
                                    'no_target' => 'No Target'
                                    ];
                                    @endphp
                                    <span class="badge {{ $statusBadge[$data['status']] }}">
                                        {{ $statusText[$data['status']] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Yearly Total Row -->
                            <tr class="table-active fw-bold">
                                <td>Total ({{ $yearParam }})</td>
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
                                        <br>
                                        <small>({{ $summary['variance_percent'] }}%)</small>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary">{{ $summary['achievement_percent'] }}%</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $statusBadge[$summary['status']] }}">
                                        {{ $statusText[$summary['status']] }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('yearSelect').addEventListener('change', function() {
        const year = this.value;
        window.location.href = `{{ route('target-analytics.user-analytics', $user->id) }}?year=${year}`;
    });
</script>

@endsection