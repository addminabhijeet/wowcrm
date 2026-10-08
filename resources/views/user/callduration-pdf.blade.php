<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Call Duration - View Report</title>
    <style>
        @page { margin: 22px 20px; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 8px; color: #111; }
        h2 { font-size: 13px; margin: 0 0 4px 0; }
        .meta { margin-bottom: 8px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #05A9A4; color: #fff; text-align: left; padding: 4px; border: 1px solid #0b7f7b; }
        td { padding: 3px 4px; border: 1px solid #ccd; }
        tr:nth-child(even) td { background: #f3f8f8; }
    </style>
</head>
<body>
    <h2>Call Duration - View Report</h2>
    <div class="meta">
        {{ $f['start_date'] }} {{ str_pad($f['start_time'], 2, '0', STR_PAD_LEFT) }}:00 to {{ $f['end_date'] }} {{ str_pad($f['end_time'], 2, '0', STR_PAD_LEFT) }}:59
        @if ($f['extension'] !== '') &nbsp;|&nbsp; Extension: {{ $f['extension'] }} @endif
        &nbsp;|&nbsp; {{ $totals['calls'] }} calls<br>
        <strong>Total Inbound Call Duration:</strong> {{ $totals['inbound'] }} &nbsp;&nbsp;
        <strong>Total Outbound Call Duration:</strong> {{ $totals['outbound'] }}<br>
        <strong>Total Inbound Answered Duration:</strong> {{ $totals['inbound_ans'] }} &nbsp;&nbsp;
        <strong>Total Outbound Answered Duration:</strong> {{ $totals['outbound_ans'] }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Sl.No</th><th>Call Date (IST)</th><th>Source</th><th>Destination</th><th>Call Duration</th>
                <th>Answered Duration</th><th>CallerID</th><th>DID</th><th>Disposition</th><th>TimeZone (IST)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $ist($r->call_date) }}</td>
                    <td>{{ $r->source }}</td>
                    <td>{{ $r->destination }}</td>
                    <td>{{ $r->call_duration }}</td>
                    <td>{{ $r->answered_duration }}</td>
                    <td>{{ $r->caller_id }}</td>
                    <td>{{ $r->did }}</td>
                    <td>{{ $r->disposition }}</td>
                    <td>{{ $ist($r->time_zone) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
