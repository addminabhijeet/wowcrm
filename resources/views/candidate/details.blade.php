@extends('layout.layout')
@php
$title='View US Accounts';
$subTitle = 'Super Admin';
$script ='<script>
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
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-24">

                <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                            Edit Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab" aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                            Change Password
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24" id="pills-notification-tab" data-bs-toggle="pill" data-bs-target="#pills-notification" type="button" role="tab" aria-controls="pills-notification" aria-selected="false" tabindex="-1">
                            Settings
                        </button>
                    </li>
                </ul>
                <form action="{{ route('users.accountant.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel">
                            <h6 class="text-md text-primary-light mb-16">Profile Image</h6>

                            <div class="mb-24 mt-16">
                                <div class="avatar-upload">
                                    <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                        <input type="file" name="image" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                        <label for="imageUpload"
                                            class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                            <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                        </label>
                                    </div>

                                    <div class="avatar-preview">
                                        <img id="imagePreview"
                                            src="{{ $candidate->image ? asset('storage/user_images/' . $candidate->image) : asset('assets/images/user-grid/user-grid-img14.png') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name <span class="text-danger-600">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control radius-8"
                                            value="{{ old('name', $candidate->name) }}" placeholder="Enter Full Name" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control radius-8"
                                            value="{{ old('email', $candidate->email) }}" placeholder="Enter email address" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="phone" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                        <input type="number" name="phone" id="phone" value="{{ old('phone', $candidate->phone) }}"
                                            min="1000000000" max="9999999999"
                                            oninput="this.value = this.value.slice(0, 10);"
                                            placeholder="Enter phone number" class="form-control radius-8">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="role" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Role <span class="text-danger-600">*</span>
                                        </label>
                                        <select name="role" id="role" class="form-control radius-8 form-select" required>
                                            <option value="" disabled>Select Role</option>
                                            <option value="junior" {{ $candidate->role == 'junior' ? 'selected' : '' }}>IT Recruiter</option>
                                            <option value="senior" {{ $candidate->role == 'senior' ? 'selected' : '' }}>IT Senior Recruiter</option>
                                            <option value="admin" {{ $candidate->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="candidate" {{ $candidate->role == 'customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="accountant" {{ $candidate->role == 'accountant' ? 'selected' : '' }}>US Accounts</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="designation" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Designation <span class="text-danger-600">*</span>
                                        </label>
                                        <select name="designation" id="designation" class="form-control radius-8 form-select" required>
                                            <option value="" disabled {{ !$candidate->designation ? 'selected' : '' }}>Select Designation</option>
                                            <option value="Accountant" {{ $candidate->designation == 'Accountant' ? 'selected' : '' }}>US Accounts</option>
                                            <option value="Admin" {{ $candidate->designation == 'Admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="Candidate" {{ $candidate->designation == 'Candidate' ? 'selected' : '' }}>Candidate</option>
                                            <option value="Junior" {{ $candidate->designation == 'Junior' ? 'selected' : '' }}>IT Recruiter</option>
                                            <option value="Senior" {{ $candidate->designation == 'Senior' ? 'selected' : '' }}>IT Senior Recruiter</option>
                                            <option value="Trainer" {{ $candidate->designation == 'Trainer' ? 'selected' : '' }}>Trainer</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel">
                            <div class="mb-20">
                                <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">New Password</label>
                                <input type="password" name="password" class="form-control radius-8" id="your-password" placeholder="Enter New Password">
                            </div>
                            <div class="mb-20">
                                <label for="confirm-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control radius-8" id="confirm-password" placeholder="Confirm Password">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-notification" role="tabpanel">
                            <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                <div class="d-flex align-items-center gap-3 justify-content-between">
                                    <span class="form-check-label line-height-1 fw-medium text-secondary-light">Account Status</span>
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $candidate->status ? 'checked' : '' }}>
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
