@extends('layout.layout')

@php
$title='Users -> IT Senior Recruiter';
$role = auth()->user()->role ?? '';
if($role === 'admin'){
    $subTitle = 'Super Admin';
} elseif ($role === 'operation') {
    $subTitle = 'Operation Manager';
} else{
    $subTitle = 'role';
}
$script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')

<div class="row gy-4">
    <div class="col-12">
        <div class="card h-100 p-0">
            <div class="card-body p-24">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-to-do-list" role="tabpanel" aria-labelledby="pills-to-do-list-tab" tabindex="0">
                        <div class="table-responsive scroll-sm">
                            <table class="table bordered-table sm-table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">Users</th>
                                        <th scope="col" class="text-center">Role</th>
                                        <th scope="col" class="text-center">Edit</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <!-- User info (avatar + name + email) -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->image ? asset('storage/app/public/' . $user->image) : asset('assets/images/users/user1.png') }}"
                                                    alt="{{ $user->name }}"
                                                    class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">{{ $user->name }}</h6>
                                                    <span class="text-sm text-secondary-light fw-medium">{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Role -->
                                        <td class="text-center">
                                            <span class="bg-primary-focus text-primary-main px-24 py-4 rounded-pill fw-medium text-sm">
                                                {{ $user->role === 'senior' ? 'IT Senior Recruiter' : ucfirst($user->role) }}
                                            </span>
                                        </td>

                                        <!-- Edit -->
                                        <td class="text-center">
                                            <div class="d-flex align-items-center gap-10 justify-content-center">
                                                <a href="{{ route('users.senior.editgroupmail', $user->id) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>

                                        <!-- Created at -->
                                        <td>
                                            <span class="text-sm text-secondary-light fw-medium">
                                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                            </span>
                                        </td>

                                        <!-- Updated at -->
                                        <td>
                                            <span class="text-sm text-secondary-light fw-medium">
                                                {{ $user->updated_at ? $user->updated_at->format('d M Y') : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
