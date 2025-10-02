@extends('layout.layout')

@php
    $title = 'Timer Settings';
    $subTitle = 'Admin';
@endphp

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-none border h-100">
            <div class="card-body p-24">
                <h5 class="mb-16">Update Timer Settings</h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('timer.update') }}" method="POST">
                    @csrf

                    <div class="mb-16">
                        <label class="form-label fw-medium">Work Day Seconds</label>
                        <input type="number" name="work_day_seconds" class="form-control rounded-pill px-16 py-6" 
                               value="{{ old('work_day_seconds', $timersetting->work_day_seconds ?? 32400) }}">
                        @error('work_day_seconds')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-16">
                        <label class="form-label fw-medium">Daily Base Time</label>
                        <input type="time" name="daily_base_time" class="form-control rounded-pill px-16 py-6" 
                               value="{{ old('daily_base_time', $timersetting->daily_base_time ?? '20:00') }}">
                        @error('daily_base_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-24 py-6">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
