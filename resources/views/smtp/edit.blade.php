@extends('layout.layout')
@php
$title = 'SMTP';
$subTitle = 'Settings - SMTP';
@endphp

@section('content')

<div class="card h-100 p-0 radius-12">
    <div class="card-body p-24">
        <div class="row gy-4">
            <div class="col-xxl-12">
                <div class="card radius-12 shadow-none border overflow-hidden">
                    <div class="card-header bg-neutral-100 border-bottom py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                        <div class="d-flex align-items-center gap-10">
                            <span class="w-36-px h-36-px bg-base rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="material-symbols:smtp-outline" class="menu-icon"></iconify-icon>
                            </span>
                            <span class="text-lg fw-semibold text-primary-light">SMTP Settings</span>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('smtp.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row gy-3">

                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">
                                        Mailer <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:email-outline"></iconify-icon>
                                        </span>
                                        <input type="text" name="mailer" class="form-control radius-8" value="{{ old('mailer', $smtp->mailer ?? 'smtp') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">
                                        Host <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:server-network"></iconify-icon>
                                        </span>
                                        <input type="text" name="host" class="form-control radius-8" value="{{ old('host', $smtp->host ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">
                                        Port <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:port"></iconify-icon>
                                        </span>
                                        <input type="number" name="port" class="form-control radius-8" value="{{ old('port', $smtp->port ?? 465) }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">
                                        Username <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:account-outline"></iconify-icon>
                                        </span>
                                        <input type="email" name="username" class="form-control radius-8" value="{{ old('username', $smtp->username ?? '') }}">
                                    </div>
                                </div>

                                <!-- Password with show/hide and copy -->
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">
                                        Password <small>(Leave blank to keep current)</small>
                                    </label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:lock-outline"></iconify-icon>
                                        </span>
                                        <input type="password" name="password" id="smtpPassword" class="form-control radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300 cursor-pointer" id="togglePassword">
                                            <iconify-icon icon="mdi:eye-outline" id="eyeIcon"></iconify-icon>
                                        </span>
                                        <span class="input-group-text bg-neutral-100 border-neutral-300 cursor-pointer" id="copyPassword">
                                            <iconify-icon icon="mdi:content-copy"></iconify-icon>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">
                                        Encryption <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:shield-outline"></iconify-icon>
                                        </span>
                                        <input type="text" name="encryption" class="form-control radius-8" value="{{ old('encryption', $smtp->encryption ?? 'ssl') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">
                                        From Address <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:email-open-outline"></iconify-icon>
                                        </span>
                                        <input type="email" name="from_address" class="form-control radius-8" value="{{ old('from_address', $smtp->from_address ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">
                                        From Name <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:account-box-outline"></iconify-icon>
                                        </span>
                                        <input type="text" name="from_name" class="form-control radius-8" value="{{ old('from_name', $smtp->from_name ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8"><span class="visibility-hidden">Save</span></label>
                                    <button type="submit" class="btn btn-primary border border-primary-600 text-md px-24 py-8 radius-8 w-100 text-center">
                                        Update
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('smtpPassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const copyPassword = document.getElementById('copyPassword');

    // Toggle show/hide
    togglePassword.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.setAttribute('icon', 'mdi:eye-off-outline');
        } else {
            passwordInput.type = 'password';
            eyeIcon.setAttribute('icon', 'mdi:eye-outline');
        }
    });

    // Copy to clipboard
    copyPassword.addEventListener('click', () => {
        passwordInput.select();
        passwordInput.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(passwordInput.value);
        alert('Password copied to clipboard!');
    });
</script>
@endpush