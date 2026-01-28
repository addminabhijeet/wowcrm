@extends('layout.layout')
@php
    $title = 'Report -> Trainer';
    $role = auth()->user()->role ?? '';
    if ($role === 'admin') {
        $subTitle = 'Super Admin';
    } elseif ($role === 'operation') {
        $subTitle = 'Operation Manager';
    } else {
        $subTitle = 'role';
    }
    $script = '<script>
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("d-none")
        });
    </script>';
@endphp

@section('content')
    <div class="user-select-none">
        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <label for="selected_date" class="fw-semibold small mb-0">Select Date:</label>
                            <input type="date" id="selected_date" value="{{ request('selected_date', date('Y-m-d')) }}"
                                class="form-control form-control-sm">
                        </div>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="allReportBtn">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> All Report
                        </a>

                    </div>
                </div>
            </div>

            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0 align-middle">
                        <thead>
                            <tr>

                                <th>S.L</th>
                                <th>Name</th>

                                <th>Role</th>
                                <th class="text-center" style="width:40px;">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="selectAllUsers">
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($juniorUsers as $index => $user)
                                <tr>

                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>

                                    <td>
                                        {{ $user->role === 'junior' ? 'IT Recruiter' : ucfirst($user->role) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input user-checkbox" type="checkbox" name="users[]"
                                                value="{{ $user->id }}" data-role="{{ $user->role }}">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const selectAll = document.getElementById("selectAllUsers");

            selectAll.addEventListener("change", function() {
                document.querySelectorAll(".user-checkbox").forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });

        });
    </script>

    <script>
        document.getElementById('allReportBtn').addEventListener('click', function() {

            // Get selected user (only one allowed)
            const selectedUser = document.querySelector('.user-checkbox:checked');

            if (!selectedUser) {
                alert('Please select a user');
                return;
            }

            const userId = selectedUser.value;

            // Get selected date
            const selectedDate = document.getElementById('selected_date').value;

            // Build URL for allreport
            const url = `{{ url('call/reports/allreport') }}/${userId}?selected_date=${selectedDate}`;

            window.location.href = url;
        });
    </script>
    <script>
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                document.querySelectorAll('.user-checkbox').forEach(other => {
                    if (other !== this) other.checked = false;
                });
            });
        });
    </script>
@endsection
