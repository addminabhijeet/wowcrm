@extends('layout.layout')

@php
    $title='Dashboard';
    $subTitle = 'Junior';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')

            <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">
                <div class="col">
                    <div class="card shadow-none border bg-gradient-start-1 h-100">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium text-primary-light mb-1">Total Resumes</p>
                                    <h6 class="mb-0">50</h6>
                                </div>
                                <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
                                </div>
                            </div>
                            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center gap-1 text-success-main">
                                    <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +6
                                </span>
                                Last 30 days total resumes
                            </p>
                        </div>
                    </div><!-- card end -->
                </div>
                <div class="col">
                    <div class="card shadow-none border bg-gradient-start-2 h-100">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium text-primary-light mb-1">Total Pending Resumes</p>
                                    <h6 class="mb-0">20</h6>
                                </div>
                                <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="fa-solid:award" class="text-white text-2xl mb-0"></iconify-icon>
                                </div>
                            </div>
                            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center gap-1 text-danger-main">
                                    <iconify-icon icon="bxs:down-arrow" class="text-xs"></iconify-icon> -1
                                </span>
                                Last 30 days total pending resumes
                            </p>
                        </div>
                    </div><!-- card end -->
                </div>
                <div class="col">
                    <div class="card shadow-none border bg-gradient-start-3 h-100">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium text-primary-light mb-1">Total Inreview Resumes</p>
                                    <h6 class="mb-0">30</h6>
                                </div>
                                <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="fluent:people-20-filled" class="text-white text-2xl mb-0"></iconify-icon>
                                </div>
                            </div>
                            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center gap-1 text-success-main">
                                    <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +1
                                </span>
                                Last 30 days total inreview resumes
                            </p>
                        </div>
                    </div><!-- card end -->
                </div>
                <div class="col">
                    <div class="card shadow-none border bg-gradient-start-4 h-100">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium text-primary-light mb-1">Total Completed Resumes</p>
                                    <h6 class="mb-0">40</h6>
                                </div>
                                <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
                                </div>
                            </div>
                            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center gap-1 text-success-main">
                                    <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +$1
                                </span>
                                Last 30 days Total completed resumes
                            </p>
                        </div>
                    </div><!-- card end -->
                </div>
            </div>

            <div class="row gy-4 mt-1">
                <div class="col-xxl-9 col-xl-12">
                    <div class="card h-100">
                        <div class="card-body p-24">

                            <div class="d-flex flex-wrap align-items-center gap-1 justify-content-between mb-16">
                                <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center active" id="pills-to-do-list-tab" data-bs-toggle="pill" data-bs-target="#pills-to-do-list" type="button" role="tab" aria-controls="pills-to-do-list" aria-selected="true">
                                            Latest Registered
                                            <span class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">35</span>
                                        </button>
                                    </li>
                                    
                                </ul>
                                
                            </div>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-to-do-list" role="tabpanel" aria-labelledby="pills-to-do-list-tab" tabindex="0">
                                    <div class="table-responsive scroll-sm">
                                        <table class="table bordered-table sm-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Candidate</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">File</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($resumes as $resume)
                                                <tr>
                                                    <!-- Candidate -->
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset('assets/images/users/user1.png') }}" 
                                                                alt="{{ $resume->candidate_name }}" 
                                                                class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                                            <div class="flex-grow-1">
                                                                <h6 class="text-md mb-0 fw-medium">{{ $resume->candidate_name }}</h6>
                                                                <span class="text-sm text-secondary-light fw-medium">ID: {{ $resume->id }}</span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Status -->
                                                    <td class="text-center">
                                                        <form action="{{ route('resumes.updateStatus', $resume->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <select name="status" onchange="this.form.submit()" 
                                                                    class="form-select form-select-sm w-auto px-24 py-6 rounded-pill fw-medium text-sm 
                                                                        {{ $resume->status }}">
                                                                @php
                                                                    $statuses = [
                                                                        'pending_review' => 'Pending Review',
                                                                        'forwarded_to_senior' => 'Forwarded to Senior',
                                                                        'customer_confirmation' => 'Customer Confirmation',
                                                                    ];
                                                                @endphp
                                                                @foreach($statuses as $key => $label)
                                                                    <option value="{{ $key }}" {{ $resume->status === $key ? 'selected' : '' }}>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </form>
                                                    </td>

                                                    <!-- File -->
                                                    <td>
                                                        <div class="d-flex gap-3">
                                                            <!-- Download button -->
                                                            <a href="{{ asset('storage/resumes/' . $resume->resume_file) }}" 
                                                            class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2"
                                                            download="{{ $resume->candidate_name }}_resume.pdf" 
                                                            rel="noopener noreferrer">
                                                                <i class="fas fa-download"></i> Download
                                                            </a>
                                                            <!-- View button -->
                                                            <a href="{{ asset('storage/resumes/' . $resume->resume_file) }}" 
                                                            class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2"
                                                            target="_blank" 
                                                            rel="noopener noreferrer">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        </div>
                                                    </td>

                                                    <!-- Updated at -->
                                                    <td>
                                                        <span class="text-sm text-secondary-light fw-medium">
                                                            {{ $resume->updated_at->format('d M Y') }}
                                                        </span>
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