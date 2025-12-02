@extends('layout.layout')
@php
    $title = $candidate->Name;
    $subTitle = $candidate->sheet_row_number;
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
                                Follow Ups
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
                            <button class="nav-link d-flex align-items-center px-24" id="pills-senddocument-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-senddocument" type="button" role="tab"
                                aria-controls="pills-senddocument" aria-selected="false" tabindex="-1">
                                Send Document
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-signeddocument-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-signeddocument" type="button" role="tab"
                                aria-controls="pills-signeddocument" aria-selected="false" tabindex="-1">
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
                                    <div class="col-12 d-flex">

                                        <!-- LEFT SIDE (Full Name + Phone) -->
                                        <div class="flex-grow-1">

                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Full
                                                    Name</label>
                                                <input type="text" name="name" class="form-control radius-8"
                                                    value="{{ old('name', $candidate->Name) }}"
                                                    placeholder="Enter Full Name" required>
                                            </div>

                                            <div class="mb-20">
                                                <label
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                                <input type="text" name="phone" class="form-control radius-8"
                                                    value="{{ old('phone', $candidate->Phone_Number) }}"
                                                    placeholder="Enter Phone" required>
                                            </div>

                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Time
                                                    Zone</label>
                                                <input type="text" name="time_zone" class="form-control radius-8"
                                                    value="{{ old('time_zone', $candidate->Time_Zone ?? '') }}"
                                                    placeholder="Enter Time Zone" required>
                                            </div>

                                        </div>

                                        <!-- SINGLE VERTICAL DIVIDER (FULL HEIGHT) -->
                                        <div class="px-4 d-flex" style="align-items: stretch;">
                                            <div style="width: 1px; background: #333; height: 100%;"></div>
                                        </div>



                                        <!-- RIGHT SIDE (Email + Location) -->
                                        <div class="flex-grow-1">

                                            <div class="mb-20">
                                                <label
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Email</label>
                                                <input type="email" name="email" class="form-control radius-8"
                                                    value="{{ old('email', $candidate->Email_Address) }}"
                                                    placeholder="Enter Email" required>
                                            </div>

                                            <div class="mb-20">
                                                <label
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Location</label>
                                                <input type="text" name="Location" class="form-control radius-8"
                                                    value="{{ old('Location', $candidate->Location) }}"
                                                    placeholder="Enter Location" required>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="tab-pane fade" id="pills-information" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 d-flex">

                                        <!-- LEFT SIDE (Full Name + Phone) -->
                                        <div class="flex-grow-1">

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
                                                    Graduation Date
                                                </label>
                                                <input type="text" name="graduation" id="graduation"
                                                    class="form-control radius-8"
                                                    value="{{ old('graduation', $candidate->Graduation_Date ?? '') }}"
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

                                        </div>

                                        <!-- SINGLE VERTICAL DIVIDER (FULL HEIGHT) -->
                                        <div class="px-4 d-flex" style="align-items: stretch;">
                                            <div style="width: 1px; background: #333; height: 100%;"></div>
                                        </div>



                                        <!-- RIGHT SIDE (Email + Location) -->
                                        <div class="flex-grow-1">

                                            <!-- Course -->
                                            <div class="mb-20">
                                                <label for="course"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Course
                                                </label>
                                                <input type="text" name="course" id="course"
                                                    class="form-control radius-8"
                                                    value="{{ old('course', $candidate->Course ?? '') }}"
                                                    placeholder="Enter Course" required>
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

                                    </div>

                                </div>

                            </div>

                            <div class="tab-pane fade" id="pills-payment" role="tabpanel">

                                <div class="row">
                                    <div class="col-12 d-flex">

                                        <!-- LEFT SIDE (Full Name + Phone) -->
                                        <div class="flex-grow-1">

                                            <div class="mb-20">
                                                <label for="amount"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Amount
                                                </label>
                                                <input type="text" name="amount" id="amount"
                                                    class="form-control radius-8"
                                                    value="${{ old('amount', $candidate->Amount ?? '') }}"
                                                    placeholder="Enter Amount" required>
                                            </div>

                                            <div class="mb-20">
                                                <label for="PaymentDate"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Payment
                                                    Date
                                                </label>
                                                <input type="date" name="PaymentDate" id="	PaymentDate"
                                                    class="form-control radius-8"
                                                    value="{{ old('PaymentDate', $candidate->PaymentDate ?? '') }}"
                                                    required>
                                            </div>

                                            <div class="mb-20">
                                                <label for="TranId"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Transaction
                                                    ID
                                                </label>
                                                <input type="text" name="TranId" id="TranId"
                                                    class="form-control radius-8"
                                                    value="{{ old('TranId', $candidate->TranId ?? '') }}"
                                                    placeholder="Enter Transaction ID" required>
                                            </div>

                                        </div>

                                        <!-- SINGLE VERTICAL DIVIDER (FULL HEIGHT) -->
                                        <div class="px-4 d-flex" style="align-items: stretch;">
                                            <div style="width: 1px; background: #333; height: 100%;"></div>
                                        </div>



                                        <!-- RIGHT SIDE (Email + Location) -->
                                        <div class="flex-grow-1">

                                            <div class="mb-20">
                                                <label for="TranRef"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Reference
                                                    Number
                                                </label>
                                                <input type="text" name="TranRef" id="TranRef"
                                                    class="form-control radius-8"
                                                    value="{{ old('TranRef', $candidate->TranRef ?? '') }}"
                                                    placeholder="Enter Reference Number" required>
                                            </div>

                                            <div class="mb-20">
                                                <label for="PaymentMethod"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Payment
                                                    Method
                                                </label>
                                                <input type="text" name="PaymentMethod" id="PaymentMethod"
                                                    class="form-control radius-8"
                                                    value="{{ old('PaymentMethod', $candidate->PaymentMethod ?? '') }}"
                                                    placeholder="Enter Payment Method" required>
                                            </div>

                                            <div class="mb-20">
                                                <label for="PayeeName"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Payee
                                                    Name
                                                </label>
                                                <input type="text" name="PayeeName" id="PayeeName"
                                                    class="form-control radius-8"
                                                    value="{{ old('PayeeName', $candidate->PayeeName ?? '') }}"
                                                    placeholder="Enter Payee Name" required>
                                            </div>

                                        </div>

                                    </div>

                                </div>







                            </div>

                            <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel">
                                <div class="mb-20">
                                    <label for="forwarded_by"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Forwarded By
                                    </label>

                                    <textarea name="forwarded_by" id="forwarded_by" class="form-control radius-8" readonly>
                                        @php
                                            $display = collect($forwardedList)
                                                ->filter(function ($item) {
                                                    return !empty($item['id']); // <-- hides 0, null, etc.
                                                })
                                                ->map(function ($item) use ($users) {
                                                    $user = $users[$item['id']] ?? null;
                                                    return $user ? $user->name : 'Unknown';
                                                })
                                                ->join('--> ');
                                        @endphp

                                        {{ $display }}
                                    </textarea>

                                </div>




                                <div class="mb-20">
                                    <label for="First_Follow_Up_Remarks"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">1st Follow Up
                                        Remarks
                                    </label>
                                    <textarea name="First_Follow_Up_Remarks" id="First_Follow_Up_Remarks" class="form-control radius-8"
                                        placeholder="Enter 1st follow up remarks" required>{{ old('First_Follow_Up_Remarks', $candidate->First_Follow_Up_Remarks ?? '') }}</textarea>
                                </div>

                                <div class="mb-20">
                                    <label for="Remark"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Remark
                                    </label>
                                    <textarea name="Remark" id="Remark" class="form-control radius-8" placeholder="Enter Remark" required>{{ old('Remark', $candidate->Remark ?? '') }}</textarea>
                                </div>


                            </div>

                            <div class="tab-pane fade" id="pills-resume" role="tabpanel">

                                <!-- Payment -->
                                <h5
                                    style="font-weight:700; color:#0d6efd; margin-bottom:10px; padding:10px 15px;
               background:#ffffff; border-left:4px solid #0d6efd; border-radius:6px;
               box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                                    Resume
                                </h5>

                                @if (!empty($candidate->resume))
                                    <div
                                        style="width:100%; margin-bottom:30px; height:85vh;
                    background:#ffffff; border-radius:10px; overflow:hidden;
                    box-shadow:0 3px 10px rgba(0,0,0,0.12);">
                                        <iframe
                                            src="{{ url('dashboard/senior/google-sheet/view-resume/' . $candidate->id) }}"
                                            style="width:100%; height:100%; border:none;" allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <p style="color:#dc3545; margin-bottom:30px; font-weight:600;">No resume available.
                                    </p>
                                @endif

                            </div>

                            <div class="tab-pane fade" id="pills-senddocument" role="tabpanel"
                                style="padding:20px; background:#f5f7fa; border-radius:10px;">

                                <!-- Acceptance -->
                                <h5
                                    style="font-weight:700; color:#0d6efd; margin-bottom:10px; padding:10px 15px;
               background:#ffffff; border-left:4px solid #0d6efd; border-radius:6px;
               box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                                    Acceptance Document
                                </h5>

                                @if (!empty($candidate->acceptance))
                                    <div
                                        style="width:100%; margin-bottom:30px; height:85vh;
                    background:#ffffff; border-radius:10px; overflow:hidden;
                    box-shadow:0 3px 10px rgba(0,0,0,0.12);">
                                        <iframe
                                            src="{{ url('dashboard/senior/google-sheet/view-acceptance/' . $candidate->id) }}"
                                            style="width:100%; height:100%; border:none;" allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <p style="color:#dc3545; margin-bottom:30px; font-weight:600;">No Acceptance available.
                                    </p>
                                @endif


                                <!-- Consultation -->
                                <h5
                                    style="font-weight:700; color:#0d6efd; margin-bottom:10px; padding:10px 15px;
               background:#ffffff; border-left:4px solid #0d6efd; border-radius:6px;
               box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                                    Consultation Document
                                </h5>

                                @if (!empty($candidate->consultation))
                                    <div
                                        style="width:100%; margin-bottom:30px; height:85vh;
                    background:#ffffff; border-radius:10px; overflow:hidden;
                    box-shadow:0 3px 10px rgba(0,0,0,0.12);">
                                        <iframe
                                            src="{{ url('dashboard/senior/google-sheet/view-consultation/' . $candidate->id) }}"
                                            style="width:100%; height:100%; border:none;" allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <p style="color:#dc3545; margin-bottom:30px; font-weight:600;">No Consultation
                                        available.</p>
                                @endif


                                <!-- Delivery -->
                                <h5
                                    style="font-weight:700; color:#0d6efd; margin-bottom:10px; padding:10px 15px;
               background:#ffffff; border-left:4px solid #0d6efd; border-radius:6px;
               box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                                    Delivery Document
                                </h5>

                                @if (!empty($candidate->delivery))
                                    <div
                                        style="width:100%; margin-bottom:30px; height:85vh;
                    background:#ffffff; border-radius:10px; overflow:hidden;
                    box-shadow:0 3px 10px rgba(0,0,0,0.12);">
                                        <iframe
                                            src="{{ url('dashboard/senior/google-sheet/view-delivery/' . $candidate->id) }}"
                                            style="width:100%; height:100%; border:none;" allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <p style="color:#dc3545; margin-bottom:30px; font-weight:600;">No Delivery available.
                                    </p>
                                @endif


                                <!-- Payment -->
                                <h5
                                    style="font-weight:700; color:#0d6efd; margin-bottom:10px; padding:10px 15px;
               background:#ffffff; border-left:4px solid #0d6efd; border-radius:6px;
               box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                                    Payment Document
                                </h5>

                                @if (!empty($candidate->payment))
                                    <div
                                        style="width:100%; margin-bottom:30px; height:85vh;
                    background:#ffffff; border-radius:10px; overflow:hidden;
                    box-shadow:0 3px 10px rgba(0,0,0,0.12);">
                                        <iframe
                                            src="{{ url('dashboard/senior/google-sheet/view-payment/' . $candidate->id) }}"
                                            style="width:100%; height:100%; border:none;" allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <p style="color:#dc3545; margin-bottom:30px; font-weight:600;">No Payment available.
                                    </p>
                                @endif

                            </div>



                            <div class="tab-pane fade" id="pills-signeddocument" role="tabpanel">

                                <!-- Resume Viewer -->
                                @if (!empty($candidate->signeddocument))
                                    <div class="w-100" style="height: 85vh;">
                                        <iframe
                                            src="{{ url('dashboard/senior/google-sheet/view-signeddocument/' . $candidate->id) }}"
                                            style="width: 100%; height: 100%; border: none;" allowfullscreen>
                                        </iframe>

                                    </div>
                                @else
                                    <p class="text-danger">No signeddocument available.</p>
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
