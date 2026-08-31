@extends('layout.layout')
@php
$title = 'Monthly Targets for ' . $user->name;
$role = auth()->user()->role ?? '';
if($role === 'admin'){
    $subTitle = 'Super Admin';
} elseif ($role === 'operation') {
    $subTitle = 'Operation Manager';
} else{
    $subTitle = $role;
}

$monthNames = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];
@endphp

@section('content')

<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div>
            <h6 class="text-lg fw-semibold mb-0">Monthly Targets - {{ $user->name }}</h6>
            <p class="text-sm text-secondary-light mb-0">{{ $user->email }} ({{ $user->role === 'junior' ? 'IT Recruiter' : 'IT Senior Recruiter' }})</p>
        </div>
        <div>
            <select id="yearSelect" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                @for($year = $currentYear - 1; $year <= $currentYear + 1; $year++)
                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-center">Target Amount</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @for($month = 1; $month <= 12; $month++)
                        @php
                            $target = $monthlyTargets->get($month);
                            $targetValue = $target ? $target->target : 1000;
                        @endphp
                        <tr class="month-row" data-month="{{ $month }}">
                            <td class="fw-semibold">{{ $monthNames[$month] }}</td>
                            <td class="text-center">
                                <div class="input-group w-auto justify-content-center" style="width: fit-content; margin: 0 auto;">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control target-input"
                                           value="{{ $targetValue }}"
                                           data-month="{{ $month }}"
                                           min="0" step="1">
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary save-target-btn"
                                        data-month="{{ $month }}" data-user="{{ $user->id }}">
                                    Save
                                </button>
                                @if($targetValue != 1000)
                                <button type="button" class="btn btn-sm btn-warning reset-target-btn"
                                        data-month="{{ $month }}" data-user="{{ $user->id }}">
                                    Reset
                                </button>
                                @endif
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <div>
                <button type="button" class="btn btn-primary" id="saveAllBtn">
                    <iconify-icon icon="ep:check" class="me-2"></iconify-icon>
                    Save All Targets
                </button>
                <button type="button" class="btn btn-secondary" id="resetAllBtn">
                    <iconify-icon icon="ep:refresh" class="me-2"></iconify-icon>
                    Reset All to Default
                </button>
            </div>
            <a href="{{ route('target.all') }}" class="btn btn-outline-primary">
                Back to All Targets
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userId = '{{ $user->id }}';
    let currentYear = '{{ $currentYear }}';

    // Year selector change
    document.getElementById('yearSelect').addEventListener('change', function() {
        currentYear = this.value;
        location.href = `{{ route('monthly-targets.user-targets', '') }}/${userId}?year=${currentYear}`;
    });

    // Save single target
    document.querySelectorAll('.save-target-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const month = this.dataset.month;
            const targetInput = document.querySelector(`input[data-month="${month}"]`);
            const target = targetInput.value;

            fetch(`/api/monthly-targets/${userId}/${currentYear}/${month}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ target: target })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Target updated successfully', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating target', 'error');
            });
        });
    });

    // Reset single target
    document.querySelectorAll('.reset-target-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const month = this.dataset.month;

            fetch(`/api/monthly-targets/${userId}/${currentYear}/${month}/reset`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`input[data-month="${month}"]`).value = 1000;
                    this.style.display = 'none';
                    showNotification('Target reset to default successfully', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error resetting target', 'error');
            });
        });
    });

    // Save all targets
    document.getElementById('saveAllBtn').addEventListener('click', function() {
        const targets = {};
        document.querySelectorAll('.target-input').forEach(input => {
            targets[input.dataset.month] = input.value;
        });

        fetch(`/api/monthly-targets/${userId}/bulk-update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                targets: targets,
                year: currentYear
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('All targets saved successfully', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error saving targets', 'error');
        });
    });

    // Reset all targets
    document.getElementById('resetAllBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset all targets to 1000?')) {
            const targets = {};
            for (let month = 1; month <= 12; month++) {
                targets[month] = 1000;
            }

            fetch(`/api/monthly-targets/${userId}/bulk-update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    targets: targets,
                    year: currentYear
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error resetting targets', 'error');
            });
        }
    });

    function showNotification(message, type) {
        // Simple notification - replace with your notification system
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;

        const alertContainer = document.createElement('div');
        alertContainer.innerHTML = alertHtml;
        document.body.insertBefore(alertContainer.firstElementChild, document.body.firstChild);
    }
});
</script>

<style>
.target-input {
    max-width: 150px;
    text-align: center;
}
</style>

@endsection
