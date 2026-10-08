@php
    // Palette taken from the Norlox Solutions logo: ink black, emerald, teal, sky.
    $serif = "Georgia, 'Times New Roman', Times, serif";
    $sans  = "'Segoe UI', Helvetica, Arial, sans-serif";
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light only">
    <title>C&amp;M Count Report</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0; mso-table-rspace: 0; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .tl { padding: 12px 18px 4px 18px; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #05A9A4; font-weight: bold; border-top: 1px solid #E3EDEC; }
        .n { padding: 5px 18px; font-family: Georgia, 'Times New Roman', serif; font-size: 15px; line-height: 21px; color: #0B1F1E; border-bottom: 1px dotted #D5E4E2; }
        .v { padding: 5px 18px; width: 64px; text-align: right; font-family: Georgia, 'Times New Roman', serif; font-size: 16px; font-weight: bold; color: #0B3B3A; border-bottom: 1px dotted #D5E4E2; }
        .ab { color: #B3261E; }
        .em { padding: 6px 18px; font-family: Georgia, 'Times New Roman', serif; font-size: 14px; font-style: italic; color: #7C9391; }
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; }
            .px { padding-left: 18px !important; padding-right: 18px !important; }
            .logo { width: 170px !important; height: auto !important; }
            .h1 { font-size: 24px !important; line-height: 30px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#E9F1F0;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:#E9F1F0;font-size:1px;line-height:1px;">
        C&amp;M count report for {{ $report['date'] }}, sent at {{ $sentAt }}.
        &#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#E9F1F0" style="background-color:#E9F1F0;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" class="container" width="640" cellpadding="0" cellspacing="0" border="0" style="width:640px;max-width:640px;">

                    {{-- Letterhead --}}
                    <tr>
                        <td bgcolor="#FFFFFF" align="center" style="background-color:#FFFFFF;border:1px solid #BFD3D1;border-bottom:0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr><td height="8" style="height:8px;line-height:8px;font-size:0;background-color:#05A9A4;background-image:linear-gradient(90deg,#00E56F 0%,#99E6FB 50%,#05A9A4 100%);">&nbsp;</td></tr>
                                <tr>
                                    <td align="center" class="px" style="padding:30px 40px 14px 40px;">
                                        <img src="{{ $logoUrl }}" alt="Norlox Solutions" class="logo" width="230" style="display:block;width:230px;max-width:100%;height:auto;margin:0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" class="px" style="padding:0 40px 26px 40px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr><td height="1" style="height:1px;line-height:1px;font-size:0;background-color:#05A9A4;">&nbsp;</td></tr>
                                            <tr><td height="3" style="height:3px;line-height:3px;font-size:0;">&nbsp;</td></tr>
                                            <tr><td height="1" style="height:1px;line-height:1px;font-size:0;background-color:#05A9A4;">&nbsp;</td></tr>
                                        </table>
                                        <div style="font-family:{{ $serif }};font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#4D6B69;padding-top:12px;">
                                            Customer Relationship Portal &nbsp;&bull;&nbsp; Official Report
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Title panel --}}
                    <tr>
                        <td bgcolor="#07100F" align="center" class="px" style="background-color:#07100F;padding:34px 40px 30px 40px;">
                            <div style="display:inline-block;background-color:#05A9A4;color:#FFFFFF;font-family:{{ $sans }};font-size:11px;font-weight:700;letter-spacing:2.5px;padding:7px 16px;border-radius:2px;">
                                {{ strtoupper($slotHour) }} IST DISPATCH
                            </div>
                            <h1 class="h1" style="margin:16px 0 6px 0;font-family:{{ $serif }};font-size:30px;line-height:36px;font-weight:normal;color:#FFFFFF;letter-spacing:.5px;">
                                C&amp;M Count Report
                            </h1>
                            <div style="font-family:{{ $serif }};font-size:15px;line-height:22px;font-style:italic;color:#99E6FB;">
                                Report date {{ $report['date'] }} &nbsp;&middot;&nbsp; {{ $sentDay }}, {{ $sentAt }}
                            </div>
                        </td>
                    </tr>
                    <tr><td height="4" style="height:4px;line-height:4px;font-size:0;background-color:#00E56F;background-image:linear-gradient(90deg,#00E56F,#05A9A4);">&nbsp;</td></tr>

                    {{-- Intro --}}
                    <tr>
                        <td bgcolor="#FBFDFC" class="px" style="background-color:#FBFDFC;border:1px solid #BFD3D1;border-top:0;border-bottom:0;padding:30px 40px 6px 40px;">
                            <p style="margin:0 0 6px 0;font-family:{{ $serif }};font-size:17px;line-height:26px;color:#0B1F1E;">Dear Team,</p>
                            <p style="margin:0 0 8px 0;font-family:{{ $serif }};font-size:16px;line-height:26px;color:#2C4745;">
                                Kindly find below the Called &amp; Mailed (C&amp;M) counts for each team, arranged by time slot.
                                An entry marked <strong style="color:#B3261E;">ab</strong> denotes that the recruiter has not yet signed in today.
                            </p>
                        @if (!empty($report['duration_note']))
                            <p style="margin:0 0 8px 0;font-family:{{ $serif }};font-size:16px;line-height:26px;color:#2C4745;">
                                Under each name, the <strong>call duration</strong> (h:mm:ss) of the same slot is also given, from the PBX call records converted to IST.
                            </p>
                        @endif
                        </td>
                    </tr>

                    {{-- One card per time slot, as on the report page --}}
                    @foreach ($report['sections'] as $section)
                        <tr>
                            <td bgcolor="#FBFDFC" class="px" style="background-color:#FBFDFC;border-left:1px solid #BFD3D1;border-right:1px solid #BFD3D1;padding:18px 40px 4px 40px;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #BFD3D1;border-top:3px solid {{ $loop->last ? '#00B35A' : '#05A9A4' }};background-color:#FFFFFF;">
                                    <tr>
                                        <td colspan="2" style="padding:13px 18px;background-color:{{ $loop->last ? '#E7F8EF' : '#F1F8F7' }};border-bottom:1px solid #D5E4E2;font-family:{{ $serif }};font-size:15px;letter-spacing:.5px;color:#0B3B3A;font-weight:bold;">
                                            {{ $section['title'] }}
                                        </td>
                                    </tr>
                                    @forelse ($section['teams'] as $team)
                                        <tr><td colspan="2" class="tl" @if ($loop->first) style="border-top:0;" @endif>Team &ndash; {{ $team['name'] }}</td></tr>
                                        @forelse ($team['rows'] as $row)
                                            <tr><td class="n">{{ $row['name'] }}</td><td class="v{{ $row['value'] === 'ab' ? ' ab' : '' }}">{{ $row['value'] }}</td></tr>
                                            @if (isset($row['duration']))
                                            <tr><td class="n" style="padding-top:0;padding-left:34px;font-size:13px;color:#4D6B69;">&#8627; Call duration</td><td class="v" style="padding-top:0;font-size:14px;color:#4D6B69;">{{ $row['duration'] }}</td></tr>
                                            @endif
                                        @empty
                                            <tr><td colspan="2" class="em">No juniors assigned.</td></tr>
                                        @endforelse
                                    @empty
                                        <tr><td colspan="2" class="em">No teams found.</td></tr>
                                    @endforelse
                                </table>
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td bgcolor="#FBFDFC" class="px" style="background-color:#FBFDFC;border:1px solid #BFD3D1;border-top:0;border-bottom:0;padding:22px 40px 30px 40px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="4" bgcolor="#00E56F" style="background-color:#00E56F;font-size:0;line-height:0;">&nbsp;</td>
                                    <td bgcolor="#EEF8F5" style="background-color:#EEF8F5;padding:14px 18px;font-family:{{ $serif }};font-size:14px;line-height:22px;color:#2C4745;">
                                        The above call numbers include only the &ldquo;C&amp;M&rdquo; counts.
                                        @if (!empty($report['duration_note']))<br>Call duration is the total talk and ring time (inbound and outbound) on the recruiter&rsquo;s extension, in h:mm:ss.@endif
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:26px 0 0 0;font-family:{{ $serif }};font-size:15px;line-height:24px;color:#2C4745;">
                                With kind regards,<br>
                                <span style="font-size:17px;font-style:italic;color:#0B3B3A;">Norlox Solutions</span><br>
                                <span style="font-family:{{ $sans }};font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#4D6B69;">Reports &amp; Records</span>
                            </p>
                        </td>
                    </tr>

                    <tr><td height="4" style="height:4px;line-height:4px;font-size:0;background-color:#05A9A4;background-image:linear-gradient(90deg,#05A9A4 0%,#99E6FB 50%,#00E56F 100%);">&nbsp;</td></tr>
                    <tr>
                        <td bgcolor="#07100F" align="center" class="px" style="background-color:#07100F;padding:26px 40px;border:1px solid #07100F;">
                            <div style="font-family:{{ $serif }};font-size:14px;letter-spacing:4px;text-transform:uppercase;color:#FFFFFF;">Norlox Solutions</div>
                            <div style="font-family:{{ $serif }};font-size:12px;line-height:19px;color:#8FB5B2;padding-top:10px;">
                                This is an automated report, issued in confidence to authorised recipients only.<br>
                                Kindly do not reply to this message.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
