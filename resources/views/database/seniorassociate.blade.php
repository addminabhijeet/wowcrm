@extends('layout.layout')

@php
    $title = 'Database -> Candidate Details';
    $subTitle = 'Senior Support Associate';
    $script = '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-to-do-list" role="tabpanel"
                            aria-labelledby="pills-to-do-list-tab" tabindex="0">

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th scope="col" class="text-center">No.</th>
                                            <th scope="col" class="text-center">Name</th>
                                            <th scope="col" class="text-center">Email Address</th>
                                            <th scope="col" class="text-center">Phone Number</th>
                                            <th scope="col" class="text-center">View</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data as $index => $row)
                                            <tr class="text-center align-middle">
                                                <!-- No -->
                                                <td>{{ $row->sheet_row_number ?? $index + 1 }}</td>

                                                <!-- Name -->
                                                <td>
                                                    <span class="fw-medium text-sm">
                                                        {{ $row->Name ?? '-' }}
                                                    </span>
                                                </td>

                                                <!-- Email -->
                                                <td class="text-center">
                                                    <span class="fw-medium text-sm text-truncate"
                                                        style="max-width: 180px; display: inline-block;">
                                                        {{ $row->Email_Address ?? '-' }}
                                                    </span>
                                                </td>


                                                <!-- Phone -->
                                                <td>
                                                    <span class="fw-medium text-sm">
                                                        {{ $row->Phone_Number ?? '-' }}
                                                    </span>
                                                </td>

                                                <!-- View -->
                                                <td>
                                                    <a href="{{ route('all.associate.candidate', [$row->id, $row->forwarded_by]) }}"
                                                        class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-medium text-sm">
                                                        View Details
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- /.table-responsive -->

                        </div> <!-- /.tab-pane -->
                    </div> <!-- /.tab-content -->

                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.col-12 -->
    </div> <!-- /.row -->
@endsection
