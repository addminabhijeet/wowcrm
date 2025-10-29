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
            <button type="button" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
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
                        <th>Role</th>
                        <th>Target</th>
                        <th>Target Date</th>
                        <th>Due Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($targetUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>{{ $user->target ?? '-' }}</td>
                        <td>{{ $user->target_date ?? '-' }}</td>
                        <td>{{ $user->due_date ?? '-' }}</td>
                        <td class="text-center">
                            {{-- ✅ Edit User modal trigger --}}
                            <button type="button" class="btn btn-sm btn-primary editUserBtn"
                                data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                data-target="{{ $user->target }}" data-target_date="{{ $user->target_date }}"
                                data-due_date="{{ $user->due_date }}" data-bs-toggle="modal"
                                data-bs-target="#userModal" data-mode="edit">
                                Edit
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Showing 1 to 10 of 12 entries</span>
            <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">
                <li class="page-item">
                    <a
                        class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md"
                        href="javascript:void(0)">
                        <iconify-icon icon="ep:d-arrow-left" class=""></iconify-icon>
                    </a>
                </li>
                <li class="page-item">
                    <a
                        class="page-link text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md bg-primary-600 text-white"
                        href="javascript:void(0)">1</a>
                </li>
                <li class="page-item">
                    <a
                        class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md"
                        href="javascript:void(0)">
                        <iconify-icon icon="ep:d-arrow-right" class=""></iconify-icon>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- ✅ Add/Edit User Modal --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border-bottom">
                <h1 class="modal-title fs-5" id="userModalLabel">Add/Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm" method="POST" action="{{ route('target.save') }}">
                @csrf
                <input type="hidden" name="id" id="user_id">
                <div class="modal-body p-24">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="user_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="user_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" id="user_role" class="form-select">
                                <option value="junior">Junior</option>
                                <option value="senior">Senior</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target</label>
                            <input type="text" name="target" id="user_target" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target Date</label>
                            <input type="date" name="target_date" id="user_target_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" id="user_due_date" class="form-control">
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

{{-- ✅ JavaScript for Add/Edit --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const userModal = document.getElementById("userModal");
        const form = document.getElementById("userForm");

        userModal.addEventListener("show.bs.modal", (event) => {
            const button = event.relatedTarget;
            const mode = button.getAttribute("data-mode");

            if (mode === "edit") {
                form.querySelector("#userModalLabel").textContent = "Edit User";
                form.querySelector("#user_id").value = button.dataset.id;
                form.querySelector("#user_name").value = button.dataset.name;
                form.querySelector("#user_email").value = button.dataset.email;
                form.querySelector("#user_role").value = button.dataset.role;
                form.querySelector("#user_target").value = button.dataset.target;
                form.querySelector("#user_target_date").value = button.dataset.target_date;
                form.querySelector("#user_due_date").value = button.dataset.due_date;
            } else {
                form.reset();
                form.querySelector("#userModalLabel").textContent = "Add New User";
                form.querySelector("#user_id").value = "";
            }
        });
    });
</script>

@endsection
