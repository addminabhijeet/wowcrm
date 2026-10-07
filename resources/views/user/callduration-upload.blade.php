@extends('layout.layout')
@php
    $title = 'Call Duration -> Upload Excel';
    $role = auth()->user()->role ?? '';
    $subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'role');
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap gap-2 align-items-center justify-content-between">
                    <h6 class="mb-0">Upload Call Records</h6>
                    <a href="{{ route('senior.excelshow') }}" class="btn btn-outline-primary btn-sm">View Group Report</a>
                </div>
                <div class="card-body p-24">
                    @if (session('success'))<div class="alert alert-success mb-16">{{ session('success') }}</div>@endif
                    @error('file')<div class="alert alert-danger mb-16">{{ $message }}</div>@enderror

                    <form method="POST" action="{{ route('senior.excelupload.store') }}" enctype="multipart/form-data"
                          onsubmit="var b=this.querySelector('button');b.disabled=true;b.innerText='Uploading...';">
                        @csrf
                        <label class="form-label fw-semibold text-sm">Export_Callrecords file (.csv)</label>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <input type="file" name="file" accept=".csv,.txt" class="form-control w-auto" required>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                        <div class="text-secondary-light text-sm mt-12">
                            Columns: Sl.No, Call Date, Source, Destination, Call Duration, Amswered Duration, CallerID, DID, Disposition, TimeZone.
                            Every row is stored; a call that is already stored (same date, source, destination, durations, caller id, DID and disposition)
                            is skipped, so overlapping exports can be uploaded safely.
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="mb-0">Stored records: {{ number_format($stats->total) }}
                        @if ($stats->total)
                            <span class="text-secondary-light text-sm fw-normal">({{ $stats->first_call }} &rarr; {{ $stats->last_call }})</span>
                        @endif
                    </h6>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0 align-middle">
                            <thead><tr><th>File</th><th class="text-center">Rows in database</th><th>Last uploaded</th></tr></thead>
                            <tbody>
                                @forelse ($recent as $r)
                                    <tr>
                                        <td>{{ $r->source_file ?: '-' }}</td>
                                        <td class="text-center">{{ number_format($r->total) }}</td>
                                        <td>{{ $r->uploaded_at }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-secondary-light py-24">Nothing uploaded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-secondary-light text-sm mt-12">Rows in database is how many stored calls came from that file (rows that were already stored count under the file that added them first).</div>
                </div>
            </div>
        </div>
    </div>
@endsection
