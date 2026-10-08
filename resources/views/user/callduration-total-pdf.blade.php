<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Total Call Duration</title>
    <style>
        @page { margin: 24px 20px; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 9px; color: #111; }
        h2 { font-size: 15px; margin: 0 0 4px 0; color: #0b2e4f; }
        h3 { font-size: 12px; margin: 14px 0 4px 0; color: #0b2e4f; border-bottom: 2px solid #0b5cad; padding-bottom: 2px; }
        .meta { margin-bottom: 6px; }
        table { width: 100%; table-layout: fixed; border-collapse: collapse; }
        thead { display: table-header-group; }
        .team { page-break-inside: avoid; margin-bottom: 8px; }
        tr { page-break-inside: avoid; }
        th { background: #0b2e4f; color: #fff; padding: 4px 2px; border: 1px solid #0b2e4f; text-align: center; font-size: 8px; }
        td { padding: 4px 2px; border: 1px solid #b9c4cf; text-align: center; font-size: 8px; white-space: nowrap; overflow: hidden; }
        td.l, th.l { text-align: left; }
        td.l { white-space: normal; padding-left: 4px; }
        td.t { font-weight: bold; background: #e4f0fb; font-size: 9px; }
        td.z { color: #7b8794; }
        tr.alt td { background: #f3f7fb; }
        tr.alt td.t { background: #e4f0fb; }
    </style>
</head>
<body>
    <h2>Total Call Duration</h2>
    <div class="meta"><strong>Date:</strong> {{ $dateLabel }} &nbsp;|&nbsp; <strong>Shift:</strong> 8:00 PM - 5:00 AM IST</div>

    @forelse ($teams as $teamName => $members)
        <div class="team">
        <h3>Team - {{ $teamName }}</h3>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">#</th>
                    <th class="l" style="width:16%">Name</th>
                    <th style="width:5%">Ext.</th>
                    <th style="width:10%">Total</th>
                    @foreach ($slotTitles as $t)
                        @php [$a, $b] = array_map(fn ($x) => strtoupper(preg_replace('/:00\s*(am|pm)/i', ' $1', trim($x))), explode('-', $t)); @endphp
                        <th style="width:7.33%">{{ $a }}<br>to {{ $b }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $m)
                    <tr class="{{ $loop->even ? 'alt' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="l"><strong>{{ $m['name'] }}</strong></td>
                        <td>{{ $m['ext'] }}</td>
                        <td class="t">{{ $fmt($m['total']) }}</td>
                        @foreach ($m['slots'] as $s)
                            <td class="{{ $s > 0 ? '' : 'z' }}">{{ $fmt($s) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @empty
        <p>No IT Recruiters found in any team.</p>
    @endforelse
</body>
</html>
