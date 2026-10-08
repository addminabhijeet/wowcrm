@extends('layout.layout')
@php
    $title = 'Call Duration -> Total Duration';
    $role = auth()->user()->role ?? '';
    $subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'role');

    // Plain text for "Copy All": one line per junior, grouped by team
    $copyText = "Total Call Duration\nDate: {$dateLabel} (8:00pm - 5:00am IST)\n\n";
    $juniorCount = 0;
    $grand = 0;
    foreach ($teams as $teamName => $members) {
        $copyText .= "Team - {$teamName}\n";
        foreach ($members as $m) {
            $copyText .= "{$m['name']} - " . $fmt($m['total']) . "\n";
            $juniorCount++;
            $grand += $m['total'];
        }
        $copyText .= "\n";
    }
@endphp

@section('content')
<style>
    .td-wrap { font-size: 17px; color: #1f2933; }
    .td-wrap .td-card { background: #fff; border: 2px solid #d5dde5; border-radius: 14px; padding: 22px 24px; margin-bottom: 22px; }
    .td-wrap label { font-size: 16px; font-weight: 700; display: block; margin-bottom: 6px; }
    .td-wrap input[type=date], .td-wrap input[type=search] { font-size: 18px; padding: 10px 14px; border: 2px solid #8896a5; border-radius: 10px; min-width: 220px; }
    .td-wrap .td-btn { font-size: 18px; font-weight: 700; padding: 12px 26px; border-radius: 10px; border: 2px solid transparent; cursor: pointer; text-decoration: none; display: inline-block; line-height: 1.2; }
    .td-wrap .td-btn:focus { outline: 4px solid #ffbf47; outline-offset: 2px; }
    .td-wrap .td-show  { background: #0b5cad; color: #fff; }
    .td-wrap .td-today { background: #fff; color: #0b5cad; border-color: #0b5cad; }
    .td-wrap .td-excel { background: #1b7a3a; color: #fff; }
    .td-wrap .td-copy  { background: #222; color: #fff; }
    .td-wrap .td-stats { display: flex; gap: 18px; flex-wrap: wrap; margin-bottom: 22px; }
    .td-wrap .td-stat { flex: 1 1 220px; background: #f3f7fb; border: 2px solid #c7d6e6; border-radius: 14px; padding: 16px 20px; }
    .td-wrap .td-stat small { display: block; font-size: 15px; font-weight: 700; color: #4a5a6a; text-transform: uppercase; letter-spacing: .5px; }
    .td-wrap .td-stat strong { font-size: 30px; color: #0b2e4f; }
    .td-wrap .td-team { font-size: 24px; font-weight: 800; color: #0b2e4f; margin: 0 0 12px; padding-bottom: 8px; border-bottom: 4px solid #0b5cad; }
    .td-wrap .td-scroll { overflow-x: auto; border: 2px solid #8896a5; border-radius: 10px; }
    .td-wrap table { width: 100%; border-collapse: collapse; font-size: 17px; }
    .td-wrap th { background: #0b2e4f; color: #fff; font-size: 15px; padding: 12px 14px; text-align: left; white-space: nowrap; position: sticky; top: 0; }
    .td-wrap td { padding: 13px 14px; border-top: 1px solid #c4ced8; white-space: nowrap; }
    .td-wrap tbody tr:nth-child(even) { background: #f3f7fb; }
    .td-wrap tbody tr:hover { background: #fff6d6; }
    .td-wrap td.td-name { font-weight: 700; }
    .td-wrap td.td-total { font-size: 21px; font-weight: 800; color: #0b2e4f; background: #e4f0fb; }
    .td-wrap td.td-zero { color: #6b7886; }
    .td-wrap .td-note { font-size: 16px; color: #3d4b59; background: #fffbe6; border: 2px solid #f0d97a; border-radius: 10px; padding: 12px 16px; margin: 0; }
</style>

<div class="container-fluid td-wrap">

    <div class="td-card">
        <form method="GET" action="{{ route('senior.totalcall') }}" style="display:flex;gap:18px;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label for="tdDate">Choose date</label>
                <input id="tdDate" type="date" name="date" value="{{ $date }}">
            </div>
            <div>
                <label for="tdSearch">Find a name</label>
                <input id="tdSearch" type="search" placeholder="Type a name" autocomplete="off">
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <button type="submit" class="td-btn td-show">Show</button>
                <a href="{{ route('senior.totalcall') }}" class="td-btn td-today">Today</a>
                <a href="{{ route('senior.totalcall.excel', ['date' => $date]) }}" class="td-btn td-excel">Download Excel</a>
                <button type="button" class="td-btn td-copy" onclick="copyAll(this)">Copy All</button>
            </div>
        </form>
    </div>

    <div class="td-stats">
        <div class="td-stat"><small>Date</small><strong>{{ $dateLabel }}</strong></div>
        <div class="td-stat"><small>Shift</small><strong>8 PM - 5 AM IST</strong></div>
        <div class="td-stat"><small>Juniors</small><strong>{{ $juniorCount }}</strong></div>
        <div class="td-stat"><small>All juniors together</small><strong>{{ $fmt($grand) }}</strong></div>
    </div>

    <p class="td-note" style="margin-bottom:22px;">
        These totals are the same as the call duration shown in each junior's top bar.
        {{ $covered ? 'Call data uploaded up to ' . $covered->format('d M, h:i A') . ' IST.' : 'No call data uploaded for this shift yet.' }}
    </p>

    @forelse ($teams as $teamName => $members)
        <div class="td-card td-team-card">
            <h2 class="td-team">Team - {{ $teamName }}</h2>
            <div class="td-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Ext. No.</th>
                            <th>Total</th>
                            @foreach ($slotTitles as $t)
                                <th>{{ $t }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $m)
                            <tr class="td-row" data-name="{{ strtolower($m['name']) }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="td-name">{{ $m['name'] }}</td>
                                <td>{{ $m['ext'] }}</td>
                                <td class="td-total">{{ $fmt($m['total']) }}</td>
                                @foreach ($m['slots'] as $s)
                                    <td class="{{ $s > 0 ? '' : 'td-zero' }}">{{ $fmt($s) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="td-card"><p class="td-note">No juniors found in any team.</p></div>
    @endforelse
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

    document.getElementById('tdSearch').addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        document.querySelectorAll('.td-row').forEach(function (tr) {
            tr.style.display = !q || tr.dataset.name.indexOf(q) !== -1 ? '' : 'none';
        });
    });
</script>
@endsection
