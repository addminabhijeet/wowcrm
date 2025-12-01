@extends('layout.layout')
@php
    $title = $candidate->Name;
    $subTitle = 'Senior Support Associate';
    $script = '<script>
        // ======================== Upload Image Start =====================
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                    $("#imagePreview").hide().fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload").change(function() {
            readURL(this);
        });

        // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on("click", function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle(".toggle-password");
        // ========================= Password Show Hide Js End ===========================
    </script>';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12"><!-- full width column -->
            <div class="card h-100">
                <div class="card-body p-24">

                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab"
                                aria-controls="pills-edit-profile" aria-selected="true">
                                Personal Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-information-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-information" type="button" role="tab"
                                aria-controls="pills-information" aria-selected="false" tabindex="-1">
                                Educational Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-payment-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-payment" type="button" role="tab"
                                aria-controls="pills-payment" aria-selected="false" tabindex="-1">
                                Payment Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab"
                                aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                Others
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-resume-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-resume" type="button" role="tab"
                                aria-controls="pills-resume" aria-selected="false" tabindex="-1">
                                Resume
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-resume-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-resume" type="button" role="tab"
                                aria-controls="pills-resume" aria-selected="false" tabindex="-1">
                                Send Document
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-resume-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-resume" type="button" role="tab"
                                aria-controls="pills-resume" aria-selected="false" tabindex="-1">
                                Signed Document
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-notification-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-notification" type="button" role="tab"
                                aria-controls="pills-notification" aria-selected="false" tabindex="-1">
                                Status
                            </button>
                        </li>
                    </ul>
                    <form action="{{ route('users.accountant.update', $candidate->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name
                                            </label>
                                            <input type="text" name="name" id="name"
                                                class="form-control radius-8" value="{{ old('name', $candidate->Name) }}"
                                                placeholder="Enter Full Name" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="email"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Email
                                            </label>
                                            <input type="email" name="email" id="email"
                                                class="form-control radius-8"
                                                value="{{ old('email', $candidate->Email_Address) }}"
                                                placeholder="Enter email address" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="phone"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                            <input type="text" name="name" id="name"
                                                class="form-control radius-8"
                                                value="{{ old('name', $candidate->Phone_Number) }}"
                                                placeholder="Enter Full Name" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="Location"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Location
                                            </label>
                                            <input type="text" name="Location" id="Location"
                                                class="form-control radius-8"
                                                value="{{ old('Location', $candidate->Location) }}"
                                                placeholder="Enter Location" required>
                                        </div>
                                    </div>

                                    <!-- Time Zone -->
                                    <div class="mb-20">
                                        <label for="time_zone"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Time Zone
                                        </label>
                                        <input type="text" name="time_zone" id="time_zone"
                                            class="form-control radius-8"
                                            value="{{ old('time_zone', $candidate->Time_Zone ?? '') }}"
                                            placeholder="Enter Time Zone" required>
                                    </div>



                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-information" role="tabpanel">


                                <!-- Relocation -->
                                <div class="mb-20">
                                    <label for="relocation"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Relocation
                                    </label>
                                    <input type="text" name="relocation" id="relocation"
                                        class="form-control radius-8"
                                        value="{{ old('relocation', $candidate->Relocation ?? '') }}"
                                        placeholder="Enter Relocation" required>
                                </div>

                                <!-- Graduation -->
                                <div class="mb-20">
                                    <label for="graduation"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Graduation
                                    </label>
                                    <input type="text" name="graduation" id="graduation"
                                        class="form-control radius-8"
                                        value="{{ old('graduation', $candidate->Graduation ?? '') }}"
                                        placeholder="Enter Graduation" required>
                                </div>

                                <!-- Immigration -->
                                <div class="mb-20">
                                    <label for="immigration"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Immigration
                                    </label>
                                    <input type="text" name="immigration" id="immigration"
                                        class="form-control radius-8"
                                        value="{{ old('immigration', $candidate->Immigration ?? '') }}"
                                        placeholder="Enter Immigration" required>
                                </div>

                                <!-- Course -->
                                <div class="mb-20">
                                    <label for="course" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Course
                                    </label>
                                    <input type="text" name="course" id="course" class="form-control radius-8"
                                        value="{{ old('course', $candidate->Course ?? '') }}" placeholder="Enter Course"
                                        required>
                                </div>

                                <!-- Qualification -->
                                <div class="mb-20">
                                    <label for="qualification"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Qualification
                                    </label>
                                    <input type="text" name="qualification" id="qualification"
                                        class="form-control radius-8"
                                        value="{{ old('qualification', $candidate->Qualification ?? '') }}"
                                        placeholder="Enter Qualification" required>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="pills-payment" role="tabpanel">

                                <div class="mb-20">
                                    <label for="amount"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Amount (in $)
                                    </label>
                                    <input type="number" name="amount" id="amount" class="form-control radius-8"
                                        value="{{ old('amount', $payment->amount ?? '') }}" placeholder="Enter Amount"
                                        required>
                                </div>

                                <div class="mb-20">
                                    <label for="payment_date"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Payment Date
                                    </label>
                                    <input type="date" name="payment_date" id="payment_date"
                                        class="form-control radius-8"
                                        value="{{ old('payment_date', $payment->payment_date ?? '') }}" required>
                                </div>

                                <div class="mb-20">
                                    <label for="transaction_id"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Transaction ID
                                    </label>
                                    <input type="text" name="transaction_id" id="transaction_id"
                                        class="form-control radius-8"
                                        value="{{ old('transaction_id', $payment->transaction_id ?? '') }}"
                                        placeholder="Enter Transaction ID" required>
                                </div>

                                <div class="mb-20">
                                    <label for="reference_number"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Reference Number
                                    </label>
                                    <input type="text" name="reference_number" id="reference_number"
                                        class="form-control radius-8"
                                        value="{{ old('reference_number', $payment->reference_number ?? '') }}"
                                        placeholder="Enter Reference Number" required>
                                </div>

                                <div class="mb-20">
                                    <label for="payment_method"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Payment Method
                                    </label>
                                    <input type="text" name="payment_method" id="payment_method"
                                        class="form-control radius-8"
                                        value="{{ old('payment_method', $payment->payment_method ?? '') }}"
                                        placeholder="Enter Payment Method" required>
                                </div>

                                <div class="mb-20">
                                    <label for="payee_name"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Payee Name
                                    </label>
                                    <input type="text" name="payee_name" id="payee_name"
                                        class="form-control radius-8"
                                        value="{{ old('payee_name', $payment->payee_name ?? '') }}"
                                        placeholder="Enter Payee Name" required>
                                </div>



                            </div>

                            <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel">

                                <div class="mb-20">
                                    <label for="forwarded_by"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Forwarded By
                                    </label>
                                    <input type="text" name="forwarded_by" id="forwarded_by"
                                        class="form-control radius-8"
                                        value="{{ old('forwarded_by', $candidate->forwarded_by ?? '') }}"
                                        placeholder="Enter name" required>
                                </div>

                                <div class="mb-20">
                                    <label for="first_follow_up"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">1st Follow Up
                                        Remarks
                                    </label>
                                    <textarea name="first_follow_up" id="first_follow_up" class="form-control radius-8"
                                        placeholder="Enter 1st follow up remarks" required>{{ old('first_follow_up', $candidate->first_follow_up ?? '') }}</textarea>
                                </div>

                                <div class="mb-20">
                                    <label for="remark"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Remark
                                    </label>
                                    <textarea name="remark" id="remark" class="form-control radius-8" placeholder="Enter remarks" required>{{ old('remark', $candidate->remark ?? '') }}</textarea>
                                </div>


                            </div>

                            <div class="tab-pane fade" id="pills-resume" role="tabpanel">

                                <!-- Resume Viewer -->
                                @if (!empty($candidate->resume))
                                    <div class="w-100" style="height: 85vh;">
                                        <iframe
                                            src="{{ url('dashboard/senior/google-sheet/view-resume/' . $candidate->id) }}"
                                            style="width: 100%; height: 100%; border: none;" allowfullscreen>
                                        </iframe>

                                    </div>
                                @else
                                    <p class="text-danger">No resume available.</p>
                                @endif

                            </div>



                            <div class="tab-pane fade" id="pills-notification" role="tabpanel">
                                <div
                                    class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                    <div class="d-flex align-items-center gap-3 justify-content-between">
                                        <span
                                            class="form-check-label line-height-1 fw-medium text-secondary-light">Candidate
                                            Status</span>
                                        <input class="form-check-input" type="checkbox" id="status" name="status"
                                            value="1" {{ $candidate->status ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
