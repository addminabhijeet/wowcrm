@extends('layout.layout')
@php
$title='Add User';
$role = auth()->user()->role ?? '';
if($role === 'admin'){
    $subTitle = 'Super Admin';
} elseif ($role === 'operation') {
    $subTitle = 'Operation Manager';
} else{
    $subTitle = 'role';
}
$script = '<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(".profile-image-preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imageUpload").change(function() {
        readURL(this);
    });

    $("#name, #email, #phone, #gender").on("input change", function() {
        $("[data-profile-field=" + this.id + "]").text(this.value || "-");
    });

    var form = document.getElementById("junior-create-form");
    var password = document.getElementById("your-password");
    var confirmation = document.getElementById("confirm-password");

    function validatePasswordConfirmation() {
        confirmation.setCustomValidity(confirmation.value && confirmation.value !== password.value
            ? "Passwords do not match." : "");
    }

    $("#your-password, #confirm-password").on("input", validatePasswordConfirmation);

    form.addEventListener("submit", function(event) {
        validatePasswordConfirmation();
        if (form.checkValidity()) {
            return;
        }

        event.preventDefault();
        var invalidInput = form.querySelector(":invalid");
        var pane = invalidInput.closest(".tab-pane");

        // Show the tab before focusing a required field inside it.
        if (pane && !pane.classList.contains("active")) {
            var tab = document.getElementById(pane.getAttribute("aria-labelledby"));
            tab.addEventListener("shown.bs.tab", function() {
                invalidInput.focus();
                invalidInput.reportValidity();
            }, { once: true });
            bootstrap.Tab.getOrCreateInstance(tab).show();
        } else {
            invalidInput.focus();
            invalidInput.reportValidity();
        }
    });
</script>';
@endphp

@section('content')

<div class="row gy-4">
    <div class="col-lg-4">
        <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100" style="padding-top: 100px;">
            <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="" class="w-100 object-fit-cover">
            <div class="pb-24 ms-16 mb-24 me-16 mt--100">
                <div class="text-center border border-top-0 border-start-0 border-end-0">
                    <img src="{{ asset('assets/images/user-grid/user-grid-img14.png') }}" alt="Profile image preview"
                        class="profile-image-preview border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover" style="max-width: 100%;">
                    <h6 class="mb-0 mt-16 text-break" data-profile-field="name">{{ old('name', '-') }}</h6>
                    <span class="text-secondary-light mb-16 text-break" data-profile-field="email">{{ old('email', '-') }}</span>
                </div>
                <div class="mt-24">
                    <h6 class="text-xl mb-16">Personal Info</h6>
                    <ul>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                            <span class="w-70 text-secondary-light fw-medium text-break">: <span data-profile-field="name">{{ old('name', '-') }}</span></span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Email</span>
                            <span class="w-70 text-secondary-light fw-medium text-break">: <span data-profile-field="email">{{ old('email', '-') }}</span></span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Ext. No.</span>
                            <span class="w-70 text-secondary-light fw-medium">: <span data-profile-field="phone">{{ old('phone', '-') }}</span></span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Role</span>
                            <span class="w-70 text-secondary-light fw-medium">: IT Recruiter</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Gender</span>
                            <span class="w-70 text-secondary-light fw-medium">: <span data-profile-field="gender">{{ old('gender', '-') }}</span></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-24">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif

                <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24 active" id="pills-add-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-add-profile" type="button" role="tab" aria-controls="pills-add-profile" aria-selected="true">Add Profile</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button" role="tab" aria-controls="pills-password" aria-selected="false">Password</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24" id="pills-settings-tab" data-bs-toggle="pill" data-bs-target="#pills-settings" type="button" role="tab" aria-controls="pills-settings" aria-selected="false">Settings</button>
                    </li>
                </ul>

                <form id="junior-create-form" action="{{ route('users.junior.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-add-profile" role="tabpanel" aria-labelledby="pills-add-profile-tab">
                            <h6 class="text-md text-primary-light mb-16">Profile Image</h6>

                            <div class="mb-24 mt-16">
                                <div class="avatar-upload">
                                    <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                        <input type="file" name="image" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                        <label for="imageUpload" title="Upload profile image" aria-label="Upload profile image" tabindex="0" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); document.getElementById('imageUpload').click(); }" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                            <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                        </label>
                                    </div>
                                    <div class="avatar-preview">
                                        <img id="imagePreview" src="{{ asset('assets/images/user-grid/user-grid-img14.png') }}" alt="Profile image preview"
                                            class="profile-image-preview w-100 h-100 rounded-circle object-fit-cover" style="background-image: none;">
                                    </div>
                                </div>
                                @error('image')<div class="text-danger text-sm mt-4">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name <span class="text-danger-600">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control radius-8" placeholder="Enter Full Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control radius-8" placeholder="Enter email address" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="phone" class="form-label fw-semibold text-primary-light text-sm mb-8">Ext. No.</label>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" inputmode="numeric" pattern="\d{3}" maxlength="3" title="Enter a 3-digit extension number" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 3);" class="form-control radius-8" placeholder="Enter 3-digit extension number">
                                        @error('phone')<div class="text-danger text-sm mt-4">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="role" class="form-label fw-semibold text-primary-light text-sm mb-8">Role <span class="text-danger-600">*</span></label>
                                        <select id="role" class="form-control radius-8 form-select" disabled>
                                            <option value="junior" selected>IT Recruiter</option>
                                        </select>
                                        <input type="hidden" name="role" value="junior">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="gender" class="form-label fw-semibold text-primary-light text-sm mb-8">Gender <span class="text-danger-600">*</span></label>
                                        <select name="gender" id="gender" class="form-control radius-8 form-select" required>
                                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
                            <div class="mb-20">
                                <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">New Password <span class="text-danger-600">*</span></label>
                                <input type="password" name="password" id="your-password" class="form-control radius-8" placeholder="Enter New Password" autocomplete="new-password" minlength="6" required>
                            </div>
                            <div class="mb-20">
                                <label for="confirm-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Confirm Password <span class="text-danger-600">*</span></label>
                                <input type="password" name="password_confirmation" id="confirm-password" class="form-control radius-8" placeholder="Confirm Password" autocomplete="new-password" required>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-settings" role="tabpanel" aria-labelledby="pills-settings-tab">
                            <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                <div class="d-flex align-items-center gap-3 justify-content-between">
                                    <label for="status" class="form-check-label line-height-1 fw-medium text-secondary-light">Account Status</label>
                                    <input type="hidden" name="status" value="0">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', 1) == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
