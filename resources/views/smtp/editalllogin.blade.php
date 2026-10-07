@extends('layout.layout')
@php
    $title = 'SMTP -> Login Mail';
    $role = auth()->user()->role ?? '';
    $subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'role');
@endphp

@section('content')
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="mb-0">Login Mail</h6>
        </div>
        <div class="card-body p-24">
            <form method="POST" action="{{ route('smtp.loginalert') }}">
                @csrf
                <p class="text-secondary-light text-sm mb-20">
                    Whenever any user (except admin) logs in successfully, an email with their name, email, role, user ID,
                    time, IP address, browser, operating system and device is sent to the addresses below.
                    Separate several addresses in one box with commas. Leave all boxes empty to turn alerts off.
                </p>

                <div class="mb-20">
                    <label class="form-label fw-semibold text-sm">To</label>
                    <input type="text" name="emails" class="form-control"
                        placeholder="main@example.com, second@example.com"
                        value="{{ old('emails', implode(', ', $recipients['to'])) }}">
                    @error('emails')<div class="text-danger-main text-sm mt-8">{{ $message }}</div>@enderror
                </div>

                <div class="mb-20">
                    <label class="form-label fw-semibold text-sm">CC</label>
                    <input type="text" name="cc" class="form-control"
                        placeholder="cc1@example.com, cc2@example.com"
                        value="{{ old('cc', implode(', ', $recipients['cc'])) }}">
                    <small class="text-secondary-light">Visible to everyone on the mail.</small>
                    @error('cc')<div class="text-danger-main text-sm mt-8">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                @if (session('success'))
                    <span class="text-success-main text-sm ms-12">{{ session('success') }}</span>
                @endif
            </form>
        </div>
    </div>
@endsection
