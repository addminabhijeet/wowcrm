@extends('layout.layout')
@php
$title='Monthly Targets';
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


<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div>
            <h6 class="text-lg fw-semibold mb-0">Monthly Targets (Default: $1000/month)</h6>
        </div>
    </div>

    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Role</th>
                        <th class="text-center">Current Month Target</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($targetUsers as $index => $user)
                    @php
                        $currentMonth = \Carbon\Carbon::now()->month;
                        $currentYear = \Carbon\Carbon::now()->year;
                        $currentTarget = \App\Models\MonthlyTarget::getTarget($user->id, $currentYear, $currentMonth, 1000);
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>
                            <span class="badge bg-primary-100 text-primary-600">
                                {{ $user->role === 'junior' ? 'IT Recruiter' : 'IT Senior Recruiter' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-100 text-primary-600 px-16 py-6">
                                ${{ number_format($currentTarget) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('monthly-targets.user-targets', $user->id) }}" class="btn btn-sm btn-primary">
                                Manage Targets
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-24">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row gy-4 mt-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span>Total Users: {{ count($targetUsers) }}</span>
        </div>
    </div>
</div>

@endsection
