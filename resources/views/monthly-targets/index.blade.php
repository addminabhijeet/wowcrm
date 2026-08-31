@extends('layout.layout')
@php
$title = 'Monthly Targets Management';
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

<!-- Quick Navigation -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card radius-12 border-0 bg-light p-0">
            <div class="card-body p-20">
                <h6 class="fw-semibold mb-3">📊 Target Management Tools</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('monthly-targets.index') }}" class="btn btn-sm btn-primary active">
                        <iconify-icon icon="ep:edit" class="me-1"></iconify-icon>
                        Monthly Targets
                    </a>
                    <a href="{{ route('target-analytics.dashboard') }}" class="btn btn-sm btn-success">
                        <iconify-icon icon="ep:data-analysis" class="me-1"></iconify-icon>
                        Analytics (Given vs Achieved)
                    </a>
                    <a href="{{ route('target-analytics.all-users') }}" class="btn btn-sm btn-info">
                        <iconify-icon icon="ep:medal" class="me-1"></iconify-icon>
                        Performance Ranking
                    </a>
                    <a href="{{ route('target.all') }}" class="btn btn-sm btn-warning">
                        <iconify-icon icon="ep:document" class="me-1"></iconify-icon>
                        View All Targets
                    </a>
                    <a href="{{ route('allowed.all') }}" class="btn btn-sm btn-outline-secondary">
                        <iconify-icon icon="ep:setting" class="me-1"></iconify-icon>
                        IP Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <h6 class="text-lg fw-semibold mb-0">Monthly Targets (Default: $1000/month)</h6>
        </div>
    </div>

    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Current Month Target</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $currentMonth = \Carbon\Carbon::now()->month;
                            $currentYear = \Carbon\Carbon::now()->year;
                            $currentTarget = \App\Models\MonthlyTarget::getTarget($user->id, $currentYear, $currentMonth, 1000);
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{
                                    $user->role === 'junior' ? 'IT Recruiter' :
                                    ($user->role === 'senior' ? 'IT Senior Recruiter' :
                                    ($user->role === 'accountant' ? 'Support' : $user->role))
                                }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-100 text-primary-600 px-16 py-6">
                                    ${{ number_format($currentTarget) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('monthly-targets.user-targets', $user->id) }}" class="btn btn-sm btn-primary">
                                    <iconify-icon icon="ep:edit" class="me-2"></iconify-icon>
                                    Manage Targets
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-24">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Total Users: {{ count($users) }}</span>
            <a href="{{ route('target.all') }}" class="btn btn-outline-primary">
                View All Targets
            </a>
        </div>
    </div>
</div>

@endsection
