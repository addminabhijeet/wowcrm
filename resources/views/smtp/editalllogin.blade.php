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

            @php
                $grouped = \App\Services\LoginDigestMail::grouped();
                $runs = \App\Services\LoginDigestMail::recentRuns();
                $pending = \App\Services\LoginDigestMail::pendingCount();
            @endphp
            <hr class="my-24">
            <h6 class="mb-8">Grouped login / logout mails</h6>
            <div class="text-secondary-light text-sm mb-12">
                When grouped, only <strong>IT Recruiter</strong> logins and logouts are mailed, as <strong>one mail per hour</strong> (IST) that had at least one of them,
                sent when that hour ends (for example the 10:00pm - 11:00pm mail at 11:00pm). Hours without activity send nothing. The addresses above are used.
                Switch it off to go back to one mail per login and logout for every user except admin.
            </div>
            <form method="POST" action="{{ route('smtp.logindigest.mode') }}" class="d-flex flex-wrap align-items-center gap-3 mb-12">
                @csrf
                <input type="hidden" name="grouped" value="{{ $grouped ? 0 : 1 }}">
                <span class="text-sm">Now: <strong>{{ $grouped ? 'Grouped (one mail per hour, juniors only)' : 'One mail per login / logout' }}</strong></span>
                <button type="submit" class="btn btn-outline-primary btn-sm">{{ $grouped ? 'Switch to one mail per login / logout' : 'Switch to grouped mails' }}</button>
                @if ($pending)<span class="text-secondary-light text-sm">{{ $pending }} event(s) waiting for the end of their hour</span>@endif
            </form>
            @if (count($runs))
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0 align-middle">
                        <thead><tr><th>Hour</th><th class="text-center">Events</th><th class="text-center">Status</th><th>Sent at (IST)</th><th>Details</th></tr></thead>
                        <tbody>
                            @foreach ($runs as $run)
                                <tr>
                                    <td>{{ $run['slot'] }}</td>
                                    <td class="text-center">{{ $run['events'] }}</td>
                                    <td class="text-center">
                                        @if ($run['status'] === 'sent')<span class="bg-success-focus text-success-main px-16 py-4 rounded-pill fw-medium text-sm">Sent</span>
                                        @else<span class="bg-danger-focus text-danger-main px-16 py-4 rounded-pill fw-medium text-sm">Failed ({{ $run['attempts'] }} tries, retrying)</span>@endif
                                    </td>
                                    <td>{{ $run['sent_at'] }}</td>
                                    <td class="text-sm {{ $run['status'] === 'sent' ? 'text-secondary-light' : 'text-danger-main' }}">{{ \Illuminate\Support\Str::limit($run['message'], 120) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
