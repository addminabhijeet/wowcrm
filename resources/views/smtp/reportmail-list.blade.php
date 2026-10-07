@extends('layout.layout')
@php
    $title = 'SMTP -> Call Report Mail List';
    $role = auth()->user()->role ?? '';
    $subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'role');
    $ist = fn ($dt) => \Carbon\Carbon::parse($dt, config('app.timezone'))->setTimezone('Asia/Kolkata');
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card p-0">
                <div class="card-body p-24">
                    <form method="GET" action="{{ route('smtp.reportmail.list') }}" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">From (IST)</label>
                            <input type="date" name="from" value="{{ $from }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">To (IST)</label>
                            <input type="date" name="to" value="{{ $to }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                @foreach (['sent' => 'Sent', 'failed' => 'Failed', 'skipped' => 'Not sent'] as $k => $v)
                                    <option value="{{ $k }}" @selected($status === $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">Slot hour (IST)</label>
                            <select name="hour" class="form-select">
                                <option value="">All</option>
                                @for ($h = 0; $h < 24; $h++)
                                    <option value="{{ $h }}" @selected((string) $hour === (string) $h)>{{ \Carbon\Carbon::createFromTime($h)->format('h:00 A') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">Trigger</label>
                            <select name="trigger" class="form-select">
                                <option value="">All</option>
                                <option value="schedule" @selected($trigger === 'schedule')>Scheduled</option>
                                <option value="manual" @selected($trigger === 'manual')>Manual</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                            <a href="{{ route('smtp.reportmail.list') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card p-0">
                <div class="card-header py-16 px-24 border-bottom d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Call Report Mail &mdash; Send List ({{ $logs->total() }})</h6>
                        <div class="text-sm mt-4">
                            <span class="text-success-main fw-semibold">{{ $counts['sent'] ?? 0 }} sent</span> &nbsp;|&nbsp;
                            <span class="text-danger-main fw-semibold">{{ $counts['failed'] ?? 0 }} failed</span> &nbsp;|&nbsp;
                            <span class="text-warning-main fw-semibold">{{ $counts['skipped'] ?? 0 }} not sent</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('smtp.editallcallreport') }}" class="btn btn-outline-primary btn-sm">Setup</a>
                        <a href="{{ route('smtp.reportmail.excel', request()->query()) }}" class="btn btn-success btn-sm">Download Excel</a>
                    </div>
                </div>
                <div class="card-body p-24">
                    @if (session('success'))<div class="text-success-main text-sm mb-12">{{ session('success') }}</div>@endif
                    @if (session('error'))<div class="text-danger-main text-sm mb-12">{{ session('error') }}</div>@endif

                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Slot (IST)</th>
                                    <th>Sent At (IST)</th>
                                    <th class="text-center">Trigger</th>
                                    <th class="text-center">Status</th>
                                    <th>Recipients</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $i => $l)
                                    <tr>
                                        <td>{{ $logs->firstItem() + $i }}</td>
                                        <td>{{ \Carbon\Carbon::parse($l->report_date)->format('d M') }}, {{ \Carbon\Carbon::createFromTime($l->report_hour)->format('h:00 A') }}</td>
                                        <td>{{ $ist($l->sent_at)->format('d M, h:i:s A') }} IST</td>
                                        <td class="text-center">{{ $l->trigger === 'manual' ? 'Manual' : 'Scheduled' }}</td>
                                        <td class="text-center">
                                            @if ($l->status === 'sent')
                                                <span class="bg-success-focus text-success-main px-16 py-4 rounded-pill fw-medium text-sm">Sent</span>
                                            @elseif ($l->status === 'failed')
                                                <span class="bg-danger-focus text-danger-main px-16 py-4 rounded-pill fw-medium text-sm">Failed</span>
                                            @else
                                                <span class="bg-warning-focus text-warning-main px-16 py-4 rounded-pill fw-medium text-sm">Not sent</span>
                                            @endif
                                        </td>
                                        <td class="text-sm">{{ $l->recipients ?: '-' }}</td>
                                        <td class="text-sm {{ $l->status === 'failed' ? 'text-danger-main' : 'text-secondary-light' }}">{{ \Illuminate\Support\Str::limit($l->message, 160) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-secondary-light py-24">No mails found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-24">{{ $logs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
