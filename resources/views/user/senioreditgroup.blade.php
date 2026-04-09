@extends('layout.layout')
@php
$title='View Profile';
$role = auth()->user()->role ?? '';
if($role === 'admin'){
$subTitle = 'Super Admin';
} elseif ($role === 'operation') {
$subTitle = 'Operation Manager';
} else{
$subTitle = 'role';
}
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
                </ul>
                <form action="{{ route('users.seniorgroup.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="role" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Role <span class="text-danger-600">*</span>
                                        </label>
                                        <select name="role" id="role" class="form-control radius-8 form-select" required>
                                            <option value="" disabled>Select Role</option>
                                            <option value="junior" {{ $user->role == 'junior' ? 'selected' : '' }}>IT Recruiter</option>
                                            <option value="senior" {{ $user->role == 'senior' ? 'selected' : '' }}>IT Senior Recruiter</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="candidate" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="accountant" {{ $user->role == 'accountant' ? 'selected' : '' }}>Support</option>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection