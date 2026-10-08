@extends('layout.layout')
@php
    $title = 'Call Duration -> Total Duration';
    $role = auth()->user()->role ?? '';
    $subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'role');

    // Plain text for "Copy All": one line per junior, grouped by team
    $copyText = "Total Call Duration\nDate: {$dateLabel} (8:00pm - 5:00am IST)\n\n";
    foreach ($teams as $teamName => $members) {
        $copyText .= "Team - {$teamName}\n";
        foreach ($members as $m) {
            $copyText .= "{$m['name']} - " . $fmt($m['total']) . "\n";
        }
        $copyText .= "\n";
    }
@endphp

@section('content')
<div class="container-fluid">

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('senior.totalcall') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-sm">Date (shift starting 8:00pm IST)</label>
                    <input type="date" name="date" value="{{ $date }}" class="form-control">
                </div>
                <div class="col-md-9 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">Show</button>
                    <a href="{{ route('senior.totalcall') }}" class="btn btn-outline-secondary">Today</a>
                    <a href="{{ route('senior.totalcall.excel', ['date' => $date]) }}" class="btn btn-success">Download Excel</a>
                    <button type="button" class="btn btn-dark" id="copyAllBtn" onclick="copyAll(this)">Copy All</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Total Call Duration</h4>
            </div>
            <p class="mb-1"><strong>Date:</strong> {{ $dateLabel }} (8:00pm - 5:00am IST)</p>
            <p class="text-sm text-secondary-light">
                Same totals as the call duration shown in each junior's navbar.
                {{ $covered ? 'Call data uploaded up to ' . $covered->format('d M, h:i A') . ' IST.' : 'No call data uploaded for this shift yet.' }}
            </p>

            @forelse ($teams as $teamName => $members)
                <h5 class="mt-4">Team - {{ $teamName }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Ext. No.</th>
                                <th>Total</th>
                                @foreach ($slotTitles as $t)
                                    <th class="text-nowrap">{{ $t }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $m['name'] }}</td>
                                    <td>{{ $m['ext'] }}</td>
                                    <td><strong>{{ $fmt($m['total']) }}</strong></td>
                                    @foreach ($m['slots'] as $s)
                                        <td>{{ $fmt($s) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <p>No juniors found.</p>
            @endforelse
        </div>
    </div>
</div>

<textarea id="copyAllText" style="position:absolute;left:-9999px;top:0;" readonly>{{ $copyText }}</textarea>

<script>
    function copyAll(button) {
        const text = document.getElementById('copyAllText').value;
        const done = function () {
            const original = button.innerHTML;
            button.innerHTML = 'Copied';
            setTimeout(function () { button.innerHTML = original; }, 1500);
        };
        navigator.clipboard.writeText(text).then(done).catch(function () {
            const ta = document.getElementById('copyAllText');
            ta.select();
            try { document.execCommand('copy'); done(); } catch (e) { alert('Failed to copy.'); }
        });
    }
</script>
@endsection
