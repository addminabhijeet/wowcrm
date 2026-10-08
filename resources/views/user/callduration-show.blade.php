@extends('layout.layout')
@php
    $title = 'Call Duration -> View Report';
    $role = auth()->user()->role ?? '';
    $subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'role');
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card p-0">
                <div class="card-body p-24">
                    <form method="GET" action="{{ route('senior.excelshow') }}" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">Start Date</label>
                            <input type="date" name="start_date" value="{{ $f['start_date'] }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">Start Time</label>
                            <select name="start_time" class="form-select">
                                @for ($h = 0; $h < 24; $h++)
                                    <option value="{{ $h }}" @selected($f['start_time'] === $h)>{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">End Date</label>
                            <input type="date" name="end_date" value="{{ $f['end_date'] }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">End Time</label>
                            <select name="end_time" class="form-select">
                                @for ($h = 0; $h < 24; $h++)
                                    <option value="{{ $h }}" @selected($f['end_time'] === $h)>{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-sm">Extension</label>
                            <input type="text" name="extension" value="{{ $f['extension'] }}" list="extList" placeholder="Extension" class="form-control" autocomplete="off">
                            <datalist id="extList">
                                @foreach ($extensions as $e)<option value="{{ $e }}">@endforeach
                            </datalist>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                            <a href="{{ route('senior.excelshow') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card p-0">
                <div class="card-header py-16 px-24 border-bottom d-flex flex-wrap gap-3 justify-content-between align-items-start">
                    <div class="text-sm">
                        <div class="mb-4">
                            <strong>Total Inbound Call Duration :</strong> {{ $totals['inbound'] }}
                            &nbsp;&nbsp; <strong>Total Outbound Call Duration :</strong> {{ $totals['outbound'] }}
                        </div>
                        <div>
                            <strong>Total Inbound Answered Duration :</strong> {{ $totals['inbound_ans'] }}
                            &nbsp;&nbsp; <strong>Total Outbound Answered Duration :</strong> {{ $totals['outbound_ans'] }}
                        </div>
                        <div class="text-secondary-light mt-4">{{ number_format($totals['calls']) }} calls</div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('senior.excelupload') }}" class="btn btn-outline-primary btn-sm">Upload Report</a>
                        <a href="{{ route('senior.excelshow.excel', $f) }}" class="btn btn-success btn-sm">Download Excel</a>
                        <a href="{{ route('senior.excelshow.pdf', $f) }}" class="btn btn-danger btn-sm">Download PDF</a>
                    </div>
                </div>
                <div class="card-body p-24">
                    @if (session('error'))<div class="alert alert-danger mb-16">{{ session('error') }}</div>@endif

                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Call Date</th>
                                    <th>Source</th>
                                    <th>Destination</th>
                                    <th>Call Duration</th>
                                    <th>Answered Duration</th>
                                    <th>CallerID</th>
                                    <th>DID</th>
                                    <th>Disposition</th>
                                    <th>TimeZone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $i => $r)
                                    <tr>
                                        <td>{{ $rows->firstItem() + $i }}</td>
                                        <td>{{ $r->call_date }}</td>
                                        <td>{{ $r->source }}</td>
                                        <td>{{ $r->destination }}</td>
                                        <td>{{ $r->call_duration }}</td>
                                        <td>{{ $r->answered_duration }}</td>
                                        <td>{{ $r->caller_id }}</td>
                                        <td>{{ $r->did }}</td>
                                        <td>{{ $r->disposition }}</td>
                                        <td>{{ $r->time_zone }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center text-secondary-light py-24">No calls found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-secondary-light text-sm mt-12">Durations are in seconds, as in the uploaded sheet. Totals above are shown as h:mm:ss.</div>

                    <div class="mt-24">{{ $rows->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
