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

                    <!-- Work Day Hours & Minutes -->
                    <div class="mb-16">
                        <label class="form-label fw-medium">Work Day Duration</label>
                        <div class="d-flex gap-2">
                            @php
                                $workSeconds = old('work_day_seconds', $timersetting->work_day_seconds ?? 32400);
                                $workHours = floor($workSeconds / 3600);
                                $workMinutes = floor(($workSeconds % 3600) / 60);
                            @endphp

                            <select name="work_day_hours" class="form-select rounded-pill px-16 py-6">
                                @for($h = 0; $h <= 24; $h++)
                                    <option value="{{ $h }}" {{ $h == $workHours ? 'selected' : '' }}>{{ $h }} h</option>
                                @endfor
                            </select>

                            <select name="work_day_minutes" class="form-select rounded-pill px-16 py-6">
                                @for($m = 0; $m < 60; $m += 5)
                                    <option value="{{ $m }}" {{ $m == $workMinutes ? 'selected' : '' }}>{{ $m }} m</option>
                                @endfor
                            </select>
                        </div>
                        @error('work_day_seconds')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Daily Base Time Hours & Minutes -->
                    <div class="mb-16">
                        <label class="form-label fw-medium">Daily Base Time</label>
                        @php
                            $baseTime = old('daily_base_time', $timersetting->daily_base_time ?? '20:00');
                            [$baseHours, $baseMinutes] = explode(':', $baseTime);
                        @endphp
                        <div class="d-flex gap-2">
                            <select name="daily_base_hours" class="form-select rounded-pill px-16 py-6">
                                @for($h = 0; $h < 24; $h++)
                                    <option value="{{ $h }}" {{ $h == $baseHours ? 'selected' : '' }}>{{ $h }} h</option>
                                @endfor
                            </select>

                            <select name="daily_base_minutes" class="form-select rounded-pill px-16 py-6">
                                @for($m = 0; $m < 60; $m += 5)
                                    <option value="{{ $m }}" {{ $m == $baseMinutes ? 'selected' : '' }}>{{ $m }} m</option>
                                @endfor
                            </select>
                        </div>
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
