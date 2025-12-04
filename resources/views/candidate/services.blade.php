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
                            <button class="nav-link d-flex align-items-center px-24" id="pills-resume-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-resume" type="button" role="tab"
                                aria-controls="pills-resume" aria-selected="false" tabindex="-1">
                                Resume Improvement
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-linkedin-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-linkedin" type="button" role="tab"
                                aria-controls="pills-linkedin" aria-selected="false" tabindex="-1">
                                LinkedIn Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-coverletter-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-coverletter" type="button" role="tab"
                                aria-controls="pills-coverletter" aria-selected="false" tabindex="-1">
                                Cover letter
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-digitalcard-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-digitalcard" type="button" role="tab"
                                aria-controls="pills-digitalcard" aria-selected="false" tabindex="-1">
                                Digital Card
                            </button>
                        </li>
                    </ul>
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel">

                                <div class="row">
                                    <div class="col-12 d-flex">

                                        <div class="flex-grow-1">

                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Full
                                                    Name</label>
                                                <input type="text" id="name" name="name"
                                                    class="form-control radius-8"
                                                    value="{{ old('name', $candidate->Name) }}"
                                                    placeholder="Enter Full Name" required>
                                            </div>

                                            <div class="mb-20">
                                                <label
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                                <input type="text" id="phone" name="phone"
                                                    class="form-control radius-8"
                                                    value="{{ old('phone', $candidate->Phone_Number) }}"
                                                    placeholder="Enter Phone" required>
                                            </div>

                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Time
                                                    Zone</label>

                                                @php
                                                    $timezoneOptions = ['EST', 'CST', 'MST', 'PST'];
                                                @endphp

                                                <select id="time_zone" name="time_zone" class="form-select radius-8"
                                                    required>
                                                    <option value="">-- Time Zone --</option>
                                                    @foreach ($timezoneOptions as $option)
                                                        <option value="{{ $option }}"
                                                            {{ old('time_zone', $candidate->Time_Zone ?? '') === $option ? 'selected' : '' }}>
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>



                                        </div>






                                        <!-- SINGLE VERTICAL DIVIDER (FULL HEIGHT) -->
                                        <div class="px-4 d-flex" style="align-items: stretch;">
                                            <div style="width: 1px; background: #ccc; height: 100%;"></div>
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

                                            <div class="mb-20 d-flex flex-column">
                                                <label
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8 w-100">&nbsp;</label>
                                                <button type="button" id="save-profile-btn"
                                                    class="btn btn-success mb-10">
                                                    Save
                                                </button>
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

                                                @php $relOptions = ['YES','NO']; @endphp

                                                <select name="relocation" id="relocation" class="form-control radius-8"
                                                    required>

                                                    <option value="">-- Relocation --</option>

                                                    @foreach ($relOptions as $option)
                                                        <option value="{{ $option }}"
                                                            {{ old('relocation', $candidate->Relocation ?? '') === $option ? 'selected' : '' }}>
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>


                                            <!-- Graduation -->
                                            <div class="mb-20">
                                                <label for="graduation"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Graduation Date
                                                </label>
                                                <input type="date" name="graduation" id="graduation"
                                                    class="form-control radius-8"
                                                    value="{{ old('graduation', $candidate->Graduation_Date ?? '') }}"
                                                    required>

                                            </div>



                                            <!-- Immigration -->
                                            @php
                                                $immOptions = [
                                                    'F1 CPT',
                                                    'F1 OPT',
                                                    'STEM OPT',
                                                    'HIB',
                                                    'B2',
                                                    'B1',
                                                    'H4',
                                                    'H4 EAD',
                                                    'GC/PR',
                                                    'USC',
                                                ];
                                            @endphp

                                            <div class="mb-20">
                                                <label for="immigration"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Immigration
                                                </label>

                                                <select name="immigration" id="immigration" class="form-select radius-8"
                                                    required>
                                                    <option value="">-- Immigration --</option>
                                                    @foreach ($immOptions as $option)
                                                        <option value="{{ $option }}"
                                                            {{ old('immigration', $candidate->Immigration ?? '') === $option ? 'selected' : '' }}>
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>

                                        <!-- SINGLE VERTICAL DIVIDER (FULL HEIGHT) -->
                                        <div class="px-4 d-flex" style="align-items: stretch;">
                                            <div style="width: 1px; background: #ccc; height: 100%;"></div>
                                        </div>



                                        <!-- RIGHT SIDE (Email + Location) -->
                                        <div class="flex-grow-1">

                                            <!-- Course -->
                                            <div class="mb-20">
                                                <label for="course"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Course
                                                </label>

                                                @php $courseOptions = ['BA','SAS','JAVA','QA','SQL','PYTHON','DOT NET']; @endphp

                                                <select name="course" id="course" class="form-select radius-8"
                                                    required>
                                                    <option value="">-- Course --</option>
                                                    @foreach ($courseOptions as $option)
                                                        <option value="{{ $option }}"
                                                            {{ old('course', $candidate->Course ?? '') === $option ? 'selected' : '' }}>
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <!-- Qualification -->
                                            <div class="mb-20">
                                                <label for="qualification"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Qualification
                                                </label>

                                                @php
                                                    $qualificationOptions = [
                                                        'Masters',
                                                        'Master of Science',
                                                        'Bachelors',
                                                        'PG',
                                                        'MBA',
                                                        'PG Diploma',
                                                        'M.Tech',
                                                        'B.Tech',
                                                        'MA',
                                                        'Associate Degree',
                                                        'Aerospace Proj. Manag.',
                                                    ];
                                                @endphp

                                                <select name="qualification" id="qualification"
                                                    class="form-control radius-8" required>
                                                    <option value="">-- Qualification --</option>
                                                    @foreach ($qualificationOptions as $option)
                                                        <option value="{{ $option }}"
                                                            {{ old('qualification', $candidate->Qualification ?? '') == $option ? 'selected' : '' }}>
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <textarea name="edu_data" id="edu_data" class="d-none">
                                                {{ old('edu_data', $candidate->edu_data ?? '') }}
                                            </textarea>

                                            <!-- Existing Save Button -->
                                            <div class="mb-20 d-flex flex-column">
                                                <label
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8 w-100">&nbsp;</label>
                                                <button type="button" id="save-edu-btn" class="btn btn-success mb-10">
                                                    Save
                                                </button>
                                            </div>


                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="tab-pane fade" id="pills-payment" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 d-flex">

                                        <!-- LEFT SIDE (Amount + Date + Tran ID) -->
                                        <div class="flex-grow-1">

                                            <div class="mb-20">
                                                <label for="amount"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Amount
                                                </label>
                                                <input type="text" name="amount" id="amount"
                                                    class="form-control radius-8"
                                                    value="{{ old('amount', $candidate->Amount ?? '') }}"
                                                    placeholder="Enter Amount" required>
                                            </div>

                                            <div class="mb-20">
                                                <label for="PaymentDate"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Payment Date
                                                </label>
                                                <input type="date" name="PaymentDate" id="PaymentDate"
                                                    class="form-control radius-8"
                                                    value="{{ old('PaymentDate', $candidate->PaymentDate ?? '') }}"
                                                    required>
                                            </div>

                                            <div class="mb-20">
                                                <label for="TranId"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Transaction ID
                                                </label>
                                                <input type="text" name="TranId" id="TranId"
                                                    class="form-control radius-8"
                                                    value="{{ old('TranId', $candidate->TranId ?? '') }}"
                                                    placeholder="Enter Transaction ID" required>
                                            </div>

                                        </div>

                                        <!-- DIVIDER -->
                                        <div class="px-4 d-flex" style="align-items: stretch;">
                                            <div style="width: 1px; background: #ccc; height: 100%;"></div>
                                        </div>

                                        <!-- RIGHT SIDE -->
                                        <div class="flex-grow-1">

                                            <div class="mb-20">
                                                <label for="TranRef"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Reference Number
                                                </label>
                                                <input type="text" name="TranRef" id="TranRef"
                                                    class="form-control radius-8"
                                                    value="{{ old('TranRef', $candidate->TranRef ?? '') }}"
                                                    placeholder="Enter Reference Number" required>
                                            </div>

                                            <div class="mb-20">
                                                <label for="PaymentMethod"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Payment Method
                                                </label>
                                                <input type="text" name="PaymentMethod" id="PaymentMethod"
                                                    class="form-control radius-8"
                                                    value="{{ old('PaymentMethod', $candidate->PaymentMethod ?? '') }}"
                                                    placeholder="Enter Payment Method" required>
                                            </div>

                                            <div class="mb-20">
                                                <label for="PayeeName"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Payee Name
                                                </label>
                                                <input type="text" name="PayeeName" id="PayeeName"
                                                    class="form-control radius-8"
                                                    value="{{ old('PayeeName', $candidate->PayeeName ?? '') }}"
                                                    placeholder="Enter Payee Name" required>
                                            </div>

                                            <!--  Save Button -->
                                            <div class="mb-20 d-flex flex-column">
                                                <label
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8 w-100">&nbsp;</label>
                                                <button type="button" id="save-pay-btn" class="btn btn-success mb-10">
                                                    Save
                                                </button>
                                            </div>


                                            <!--  Hidden field (same concept as followups) -->
                                            <textarea name="payment_data" id="payment_data" class="d-none">
                                                {{ old('payment_data', $candidate->payment_data ?? '') }}
                                            </textarea>

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
                                        placeholder="Enter 1st follow up remarks" required readonly>{{ old('First_Follow_Up_Remarks', $candidate->First_Follow_Up_Remarks ?? '') }}</textarea>
                                </div>

                                <div class="mb-20">
                                    <label for="Remark"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Remark
                                    </label>
                                    <textarea name="Remark" id="Remark" class="form-control radius-8" placeholder="Enter Remark" required readonly>{{ old('Remark', $candidate->Remark ?? '') }}</textarea>
                                </div>

                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Follow Up Remarks (Calendar)
                                    </label>

                                    <input type="text" id="followup-calendar" class="form-control radius-8 mb-2"
                                        placeholder="Select date">

                                    <textarea id="followup-remark" class="form-control radius-8 mb-2" rows="4"
                                        placeholder="Select a date to view remarks" readonly></textarea>

                                    <textarea id="new-remark" class="form-control radius-8 mb-2" rows="2" placeholder="Add new remark"></textarea>
                                    <button type="button" id="add-remark-btn" class="btn btn-primary mb-10">Add
                                        Remark</button>

                                    <!-- Hidden input to store all followups for form submission -->
                                    <textarea name="followups" id="followups" class="d-none">{{ old('followups', $candidate->followup ?? '') }}</textarea>
                                </div>

                                <link rel="stylesheet"
                                    href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

                                <!-- Payment -->
                                <h5
                                    style="font-weight:700; color:#0d6efd; margin-bottom:10px; padding:10px 15px;
                                background:#ffffff; border-left:4px solid #0d6efd; border-radius:6px;
                                box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                                    Resume Update
                                </h5>

                                @if (!empty($candidate->updateresume))
                                    <div
                                        style="width:100%; margin-bottom:30px; height:85vh;
                                background:#ffffff; border-radius:10px; overflow:hidden;
                                box-shadow:0 3px 10px rgba(0,0,0,0.12);">
                                        <iframe
                                            src="{{ url('dashboard/senior/google-sheet/view-updateresume/' . $candidate->id) }}"
                                            style="width:100%; height:100%; border:none;" allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <p style="color:#dc3545; margin-bottom:30px; font-weight:600;">No updated resume
                                        available.
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
                                    <p class="text-danger">No signed document available.</p>
                                @endif

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function initSingleLocationAutocomplete() {
                const $input = $('input[name="Location"]');

                function applyCss(value) {
                    if (!value) {
                        $input.removeClass('valid invalid').addClass('neutral');
                    } else {
                        $input.removeClass('invalid neutral').addClass('valid');
                    }
                }

                // Initial CSS state
                applyCss($input.val());

                $input.on('input', function() {
                    const q = $(this).val().trim();
                    applyCss(q);

                    if (q.length < 2) {
                        $('#location-suggestions').remove();
                        return;
                    }

                    const key = 'pk.e91481c6e5f0a93703159ae988e641a0';

                    $.getJSON(
                            `https://us1.locationiq.com/v1/autocomplete.php?key=${key}&q=${encodeURIComponent(q)}&limit=5&dedupe=1&normalizecity=1&accept-language=en`
                        )
                        .done(function(results) {

                            $('#location-suggestions').remove();

                            const $list =
                                $(
                                    '<div id="location-suggestions" class="list-group" style="position:absolute; z-index:9999; max-height:200px; overflow:auto;"></div>'
                                );

                            results.forEach(r => {
                                const addr = r.address || {};
                                const city = addr.city || addr.town || addr.village || '';
                                const state = addr.state || addr.region || '';
                                const country = addr.country || '';
                                const display = [city, state, country].filter(Boolean).join(
                                    ', ');

                                const item = $(
                                        '<a href="#" class="list-group-item list-group-item-action"></a>'
                                    )
                                    .text(display || r.display_name);

                                item.on('click', function(e) {
                                    e.preventDefault();
                                    $input.val(display || r.display_name);
                                    applyCss(display || r.display_name);
                                    $input.css('background-color', '#d4edda');
                                    $('#location-suggestions').remove();
                                });

                                $list.append(item);
                            });

                            $('body').append($list);

                            const offset = $input.offset();
                            $list.css({
                                top: offset.top + $input.outerHeight(),
                                left: offset.left,
                                width: $input.outerWidth()
                            });

                        })
                        .fail(() => $('#location-suggestions').remove());
                });

                $input.on('blur', function() {
                    setTimeout(() => $('#location-suggestions').remove(), 200);
                });
            }

            // Initialize when page is ready
            initSingleLocationAutocomplete();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* -----------------------------
                PHONE: format + validate
            ----------------------------- */
            const phoneInput = document.querySelector("#phone");
            if (phoneInput) {
                phoneInput.addEventListener("input", function(e) {
                    const clean = e.target.value.replace(/\D/g, "");
                    e.target.value = formatPhoneNumber(clean);
                    validatePhoneInput(e.target);
                });
            }

            /* -----------------------------
                NAME: only letters + spaces
            ----------------------------- */
            const nameInput = document.querySelector("#name");
            if (nameInput) {
                validateNameInput(nameInput); // initial validation
                nameInput.addEventListener("input", function(e) {
                    e.target.value = e.target.value.toLowerCase().replace(/[^a-zA-Z\s]/g, "");
                    validateNameInput(e.target);
                });
            }


            /* -----------------------------
                EMAIL: lowercase + validation
            ----------------------------- */
            const emailInput = document.querySelector("input[name='email']");
            if (emailInput) {
                emailInput.addEventListener("input", function(e) {
                    e.target.value = e.target.value.toLowerCase();
                    validateEmailInput(e.target);
                });
            }

            /* -----------------------------
                LOCATION: allow letters, nums, commas
            ----------------------------- */
            const locationInput = document.querySelector("input[name='Location']");
            if (locationInput) {
                locationInput.addEventListener("input", function(e) {
                    e.target.value = e.target.value.replace(/[^a-zA-Z0-9\s,.-]/g, "");
                });
            }

            /* -----------------------------
                AMOUNT: digits + 1 dot + $ prefix
            ----------------------------- */
            const amountInput = document.querySelector("#amount");
            if (amountInput) {
                amountInput.addEventListener("input", function(e) {
                    validateAmountInput(e.target);
                });
            }

            /* -----------------------------
                TRANSACTION ID, REF, METHOD,
                PAYEE NAME: restrict special chars
            ----------------------------- */
            ["TranId", "TranRef", "PaymentMethod", "PayeeName"].forEach(id => {
                const el = document.querySelector("#" + id);
                if (el) {
                    el.addEventListener("input", function(e) {
                        e.target.value = e.target.value.replace(/[^a-zA-Z0-9\s\-]/g, "");
                    });
                }
            });

            /* -----------------------------
                COURSE + QUALIFICATION:
                Only valid select, so no input restriction needed
            ----------------------------- */

            /* -----------------------------
                GRADUATION DATE: restrict letters
            ----------------------------- */
            const gradInput = document.querySelector("#graduation");
            if (gradInput) {
                gradInput.addEventListener("input", function(e) {
                    e.target.value = e.target.value.replace(/[^0-9\/\-]/g, "");
                });
            }

        });
    </script>
@endsection
