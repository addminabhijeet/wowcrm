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
                                Edit Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab"
                                aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                Information
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
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Email </label>
                                            <input type="email" name="email" id="email"
                                                class="form-control radius-8" value="{{ old('email', $candidate->Email_Address) }}"
                                                placeholder="Enter email address" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="phone"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                            <input type="text" name="name" id="name"
                                                class="form-control radius-8" value="{{ old('name', $candidate->Phone_Number) }}"
                                                placeholder="Enter Full Name" required>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel">
                                <div class="mb-20">
                                    <label for="your-password"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">New Password</label>
                                    <input type="password" name="password" class="form-control radius-8"
                                        id="your-password" placeholder="Enter New Password">
                                </div>
                                <div class="mb-20">
                                    <label for="confirm-password"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Confirm
                                        Password</label>
                                    <input type="password" name="password_confirmation" class="form-control radius-8"
                                        id="confirm-password" placeholder="Confirm Password">
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-notification" role="tabpanel">
                                <div
                                    class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                    <div class="d-flex align-items-center gap-3 justify-content-between">
                                        <span class="form-check-label line-height-1 fw-medium text-secondary-light">Candidate
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
