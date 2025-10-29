@extends('layout.layout')
@php
$title = 'User Target';
$subTitle = 'User Target';
$script = '<script>
    $(".remove-item-btn").on("click", function() {
        $(this).closest("tr").addClass("d-none")
    });
</script>';
@endphp

@section('content')

<div class="card h-100 p-0 radius-12">
    <div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
            <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                <option>1</option>
            </select>

            {{-- ✅ Button to open Add Target modal --}}
            <button type="button"
                class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#userModal" data-mode="add">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Add Target
            </button>
        </div>
    </div>

    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Target</th>
                        <th>Target Month</th>
                        <th>Due Month</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $targets = $targetUsers->target ? explode(' | ', $targetUsers->target) : [];
                        $targetDates = $targetUsers->target_date ? explode(' | ', $targetUsers->target_date) : [];
                        $dueDates = $targetUsers->due_date ? explode(' | ', $targetUsers->due_date) : [];
                    @endphp

                    @foreach($targets as $index => $target)
                    <tr>
                        <td>{{ $targetUsers->name }}</td>
                        <td>{{ $target }}</td>
                        <td>{{ $targetDates[$index] ?? '-' }}</td>
                        <td>{{ $dueDates[$index] ?? '-' }}</td>
                        <td class="text-center">
                            <button type="button"
                                class="btn btn-sm btn-warning d-flex align-items-center gap-1"
                                data-bs-toggle="modal"
                                data-bs-target="#userModal"
                                data-mode="edit"
                                data-id="{{ $targetUsers->id }}"
                                data-index="{{ $index }}"
                                data-target="{{ $target }}"
                                data-target_date="{{ $targetDates[$index] ?? '' }}"
                                data-due_date="{{ $dueDates[$index] ?? '' }}">
                                <iconify-icon icon="mdi:pencil" class="text-lg"></iconify-icon>
                                Edit
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ✅ Add/Edit Target Modal --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border-bottom">
                <h1 class="modal-title fs-5" id="userModalLabel">Add/Edit Target</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- ✅ Form sends only target data --}}
            <form id="userForm" method="POST" action="{{ route('target.save', $targetUsers->id) }}">
                @csrf
                <input type="hidden" name="id" id="user_id" value="{{ $targetUsers->id }}">
                <input type="hidden" name="index" id="user_index">

                <div class="modal-body p-24">
                    <div class="row">
                        {{-- Target --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Target</label>
                            <input type="number" name="target" id="user_target" class="form-control" required>
                        </div>

                        {{-- Target Month --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Target Month</label>
                            <input type="month" name="target_date" id="user_target_date" class="form-control" required>
                        </div>

                        {{-- Due Month --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Due Month</label>
                            <input type="month" name="due_date" id="user_due_date" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ Script for Add/Edit Target Modal --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const userModal = document.getElementById("userModal");
    const form = document.getElementById("userForm");
    const modalTitle = document.getElementById("userModalLabel");

    userModal.addEventListener("show.bs.modal", (event) => {
        const button = event.relatedTarget;
        const mode = button.getAttribute("data-mode");

        if (mode === "edit") {
            modalTitle.textContent = "Edit Target";
            form.querySelector("#user_id").value = button.dataset.id;
            form.querySelector("#user_index").value = button.dataset.index;
            form.querySelector("#user_target").value = button.dataset.target;
            form.querySelector("#user_target_date").value = button.dataset.target_date;
            form.querySelector("#user_due_date").value = button.dataset.due_date;
        } else {
            modalTitle.textContent = "Add Target";
            form.reset();
            form.querySelector("#user_id").value = "{{ $targetUsers->id }}";
            form.querySelector("#user_index").value = "";
        }
    });
});
</script>

@endsection
