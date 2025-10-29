@extends('layout.layout')
@php
$title='All Junior Call Report';
$subTitle = 'All Junior Call Report';
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

            {{-- ✅ Button to open Add User modal --}}
            <button type="button"
                class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#userModal" data-mode="add">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Add User
            </button>
        </div>
    </div>

    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Designation</th>
                        <th>Target</th>
                        <th>Target Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $targetUsers->name }}</td>
                        <td>{{ $targetUsers->email }}</td>
                        <td>{{ $targetUsers->phone ?? '-' }}</td>
                        <td>{{ ucfirst($targetUsers->role) }}</td>
                        <td>{{ $targetUsers->designation ?? '-' }}</td>
                        <td>{{ $targetUsers->target ?? '-' }}</td>
                        <td>{{ $targetUsers->target_date ?? '-' }}</td>
                        <td>{{ $targetUsers->due_date ?? '-' }}</td>
                        <td>
                            @if($targetUsers->status == 1)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- ✅ Add/Edit Target Modal (modified version) --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border-bottom">
                <h1 class="modal-title fs-5" id="userModalLabel">Add/Edit Target</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- ✅ Keep the same route but only pass target fields --}}
            <form id="userForm" method="POST" action="{{ route('target.save', $targetUsers->id) }}">
                @csrf
                <input type="hidden" name="id" id="user_id" value="{{ $targetUsers->id }}">

                <div class="modal-body p-24">
                    <div class="row">
                        {{-- Target --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Target</label>
                            <input type="number" name="target" id="user_target" class="form-control" required>
                        </div>

                        {{-- Target Date --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Target Date</label>
                            <input type="date" name="target_date" id="user_target_date" class="form-control" required>
                        </div>

                        {{-- Due Date --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" id="user_due_date" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
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

        userModal.addEventListener("show.bs.modal", (event) => {
            const button = event.relatedTarget;
            const mode = button.getAttribute("data-mode");

            if (mode === "edit") {
                form.querySelector("#userModalLabel").textContent = "Edit Target";
                form.querySelector("#user_id").value = button.dataset.id;
                form.querySelector("#user_target").value = button.dataset.target;
                form.querySelector("#user_target_date").value = button.dataset.target_date;
                form.querySelector("#user_due_date").value = button.dataset.due_date;
            } else {
                form.reset();
                form.querySelector("#userModalLabel").textContent = "Add Target";
                form.querySelector("#user_id").value = "{{ $targetUsers->id }}";
            }
        });
    });
</script>

@endsection