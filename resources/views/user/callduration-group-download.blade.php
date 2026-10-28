<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recruiter Call Report - {{ $dateLabel }}</title>
    <style>
        @page { size: A4 landscape; margin: 10mm 7mm; }
        body { margin: 0; font-family: Georgia, "DejaVu Serif", serif; font-size: 9pt; line-height: 1.15; color: #000; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 0.6pt solid #000; text-align: center; vertical-align: middle; padding: 3pt 2pt; }
        th { background: #daf2d0; font-size: 8pt; font-weight: bold; height: 34pt; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        .date { font-size: 12pt; font-weight: bold; padding: 3pt; }
        .leader { background: #ddebf7; }
        .metric { font-weight: bold; }
    </style>
</head>
<body>
<table>
    <colgroup>
        <col style="width:21%"><col style="width:10%"><col style="width:9%">
        <col style="width:9%"><col style="width:11%"><col style="width:11%">
        <col style="width:12%"><col style="width:12%"><col style="width:5%">
    </colgroup>
    <thead>
        <tr>
            <th>Name</th><th>Date</th><th>Day</th>
            <th>Called &amp; Mailed</th><th>Total incoming calls</th>
            <th>Total outgoing calls</th><th>Total Inbound Answered Duration</th>
            <th>Total Outbound Answered Duration</th><th>EXT</th>
        </tr>
        <tr><td class="date" colspan="8">{{ $dateLabel }}</td><td></td></tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <td @class(['leader' => $row['leader']])>{{ $row['name'] }}</td>
                <td>{{ $dateLabel }}</td><td>{{ $day }}</td>
                <td class="metric">{{ $row['called_mailed'] }}</td>
                <td class="metric">{{ $row['incoming'] }}</td>
                <td class="metric">{{ $row['outgoing'] }}</td>
                <td class="metric">{{ $row['inbound_duration'] === null ? '' : $fmt($row['inbound_duration']) }}</td>
                <td class="metric">{{ $row['outbound_duration'] === null ? '' : $fmt($row['outbound_duration']) }}</td>
                <td class="metric">{{ $row['extension'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
