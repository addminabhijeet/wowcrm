@extends('layout.layout')

@php
    $title = 'Users -> IT Senior Recruiter';
    $subTitle = 'Super Admin';
    $script = '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card h-100 p-0">
                <div class="card-body p-24">

                    <div class="d-flex flex-wrap align-items-center gap-1 justify-content-between mb-16">
                        <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center active" id="pills-to-do-list-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-to-do-list" type="button" role="tab"
                                    aria-controls="pills-to-do-list" aria-selected="true">
                                    Active
                                    <span
                                        class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">35</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-to-do-list" role="tabpanel"
                            aria-labelledby="pills-to-do-list-tab" tabindex="0">
                            <div class="table-responsive scroll-sm">
                                <table class="table bordered-table sm-table mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email Address</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col">View</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data as $row)
                                            <tr>
                                                <!-- No -->
                                                <td class="text-center">
                                                    {{ $row->sheet_row_number ?? '-' }}
                                                </td>

                                                <!-- Name -->
                                                <td class="text-center">
                                                    <span class="fw-medium text-sm">
                                                        {{ $row->Name ?? '-' }}
                                                    </span>
                                                </td>

                                                <!-- Email -->
                                                <td class="text-center">
                                                    <span class="fw-medium text-sm">
                                                        {{ $row->Email_Address ?? '-' }}
                                                    </span>
                                                </td>

                                                <!-- Phone -->
                                                <td class="text-center">
                                                    <span class="fw-medium text-sm">
                                                        {{ $row->Phone_Number ?? '-' }}
                                                    </span>
                                                </td>

                                                <!-- View -->
                                                <td class="text-center">
                                                    <a href="{{ asset($row->resume) }}" target="_blank"
                                                        class="bg-primary-focus text-primary-main px-24 py-4 rounded-pill fw-medium text-sm">
                                                        View Resume
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
