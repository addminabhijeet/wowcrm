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
                        {{-- Status Message Container --}}
                        <div id="smtpAlertContainer">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                        </div>

                        {{-- SMTP Update Form --}}
                        <form action="{{ route('smtp.update') }}" method="POST" class="mb-5">
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
                                        <input type="text" name="mailer" class="form-control radius-8"
                                            value="{{ old('mailer', $smtp->mailer ?? 'smtp') }}">
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
                                        <input type="text" name="host" class="form-control radius-8"
                                            value="{{ old('host', $smtp->host ?? '') }}">
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
                                        <input type="number" name="port" class="form-control radius-8"
                                            value="{{ old('port', $smtp->port ?? 465) }}">
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
                                        <input type="email" name="username" class="form-control radius-8"
                                            value="{{ old('username', $smtp->username ?? '') }}">
                                    </div>
                                </div>

                                {{-- Password Field --}}
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
                                        <input type="text" name="encryption" class="form-control radius-8"
                                            value="{{ old('encryption', $smtp->encryption ?? 'ssl') }}">
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
                                        <input type="email" name="from_address" class="form-control radius-8"
                                            value="{{ old('from_address', $smtp->from_address ?? '') }}">
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
                                        <input type="text" name="from_name" class="form-control radius-8"
                                            value="{{ old('from_name', $smtp->from_name ?? '') }}">
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

                        {{-- AJAX TEST EMAIL FORM --}}
                        <hr class="my-4">
                        <h5 class="text-primary-light mb-3">Send Test Email</h5>
                        <form id="testSmtpForm">
                            @csrf
                            <div class="row gy-3 align-items-end">
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-primary-light text-md mb-8">Recipient Email</label>
                                    <div class="input-group radius-8">
                                        <span class="input-group-text bg-neutral-100 border-neutral-300">
                                            <iconify-icon icon="mdi:email-send-outline"></iconify-icon>
                                        </span>
                                        <input type="email" name="test_email" id="testEmail" class="form-control radius-8"
                                            placeholder="Enter test email address" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" id="sendTestBtn" class="btn btn-success border border-success-600 text-md px-24 py-8 radius-8 w-100 text-center">
                                        <span id="btnText">Send Test Email</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div> <!-- card-body -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const input = $('#smtpPassword');
        const icon = $('#eyeIcon');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.attr('icon', 'mdi:eye-off-outline');
        } else {
            input.attr('type', 'password');
            icon.attr('icon', 'mdi:eye-outline');
        }
    });

    // Copy password to clipboard
    $('#copyPassword').on('click', function() {
        const input = document.getElementById('smtpPassword');
        input.select();
        document.execCommand('copy');
        alert('Password copied to clipboard!');
    });

    // AJAX: Send Test Email
    $('#testSmtpForm').on('submit', function(e) {
        e.preventDefault();

        const email = $('#testEmail').val();
        const btn = $('#sendTestBtn');
        const btnText = $('#btnText');

        btn.prop('disabled', true);
        btnText.text('Sending...');

        $.ajax({
            url: "{{ route('smtp.test') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                test_email: email
            },
            success: function(response) {
                $('#smtpAlertContainer').html(
                    `<div class="alert alert-success">${response.message}</div>`
                );
            },
            error: function(xhr) {
                let message = 'Failed to send test email.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                $('#smtpAlertContainer').html(
                    `<div class="alert alert-danger">${message}</div>`
                );
            },
            complete: function() {
                btn.prop('disabled', false);
                btnText.text('Send Test Email');
            }
        });
    });
});
</script>
@endpush
