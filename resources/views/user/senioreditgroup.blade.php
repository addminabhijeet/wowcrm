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
    <div class="col-12">
        <div class="card h-100">
            <div class="card-body p-24">
                <form action="{{ route('users.seniorgroup.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Assign Juniors
                                        </label>

                                        <select name="group[]" class="form-control radius-8 form-select" multiple>
                                            @foreach($juniors as $junior)
                                            <option value="{{ $junior->id }}"
                                                {{ in_array($junior->id, $user->group ?? []) ? 'selected' : '' }}>
                                                {{ $junior->name }} ({{ $junior->email }})
                                            </option>
                                            @endforeach
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

<div class="row gy-4">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-body p-24">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel">
                        <div class="row">
                            <div class="col-12 mt-3">
                                <h6>Assigned Juniors</h6>

                                @if(!empty($user->group))
                                @foreach($juniors->whereIn('id', $user->group) as $junior)
                                <div class="d-flex justify-content-between align-items-center border p-2 mb-2 radius-8">
                                    <div>
                                        {{ $junior->name }} ({{ $junior->email }})
                                    </div>

                                    <form action="{{ route('users.seniorgroup.remove', [$user->id, $junior->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </div>
                                @endforeach
                                @else
                                <p>No juniors assigned.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection