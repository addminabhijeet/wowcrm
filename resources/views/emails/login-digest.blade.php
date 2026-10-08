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
    <title>Login / Logout Summary</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0; mso-table-rspace: 0; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        @media only screen and (max-width: 640px) {
            .container { width: 100% !important; }
            .px { padding-left: 16px !important; padding-right: 16px !important; }
            .logo { width: 170px !important; height: auto !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#E9F1F0;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:#E9F1F0;font-size:1px;line-height:1px;">
        {{ $logins }} login(s) and {{ $logouts }} logout(s) between {{ $label }} IST.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#E9F1F0" style="background-color:#E9F1F0;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" class="container" width="680" cellpadding="0" cellspacing="0" border="0" style="width:680px;max-width:680px;">

                    {{-- Letterhead --}}
                    <tr>
                        <td bgcolor="#FFFFFF" align="center" style="background-color:#FFFFFF;border:1px solid #BFD3D1;border-bottom:0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr><td height="8" style="height:8px;line-height:8px;font-size:0;background-color:#05A9A4;background-image:linear-gradient(90deg,#00E56F 0%,#99E6FB 50%,#05A9A4 100%);">&nbsp;</td></tr>
                                <tr>
                                    <td align="center" class="px" style="padding:26px 40px 22px 40px;">
                                        <img src="{{ $logoUrl }}" alt="Norlox Solutions" class="logo" width="210" style="display:block;width:210px;max-width:100%;height:auto;margin:0 auto;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Title panel --}}
                    <tr>
                        <td bgcolor="#07100F" align="center" class="px" style="background-color:#07100F;padding:30px 40px 26px 40px;">
                            <div style="display:inline-block;background-color:#05A9A4;color:#FFFFFF;font-family:{{ $sans }};font-size:11px;font-weight:700;letter-spacing:2.5px;padding:7px 16px;border-radius:2px;">
                                {{ strtoupper($label) }} IST
                            </div>
                            <h1 style="margin:14px 0 6px 0;font-family:{{ $serif }};font-size:28px;line-height:34px;font-weight:normal;color:#FFFFFF;">Login &amp; Logout Summary</h1>
                            <div style="font-family:{{ $serif }};font-size:15px;line-height:22px;font-style:italic;color:#99E6FB;">
                                {{ $day }} &nbsp;&middot;&nbsp; {{ $logins }} login{{ $logins === 1 ? '' : 's' }} &nbsp;&middot;&nbsp; {{ $logouts }} logout{{ $logouts === 1 ? '' : 's' }} &nbsp;&middot;&nbsp; {{ $people }} recruiter{{ $people === 1 ? '' : 's' }}
                            </div>
                        </td>
                    </tr>
                    <tr><td height="4" style="height:4px;line-height:4px;font-size:0;background-color:#00E56F;background-image:linear-gradient(90deg,#00E56F,#05A9A4);">&nbsp;</td></tr>

                    {{-- Events --}}
                    <tr>
                        <td bgcolor="#FBFDFC" class="px" style="background-color:#FBFDFC;border:1px solid #BFD3D1;border-top:0;border-bottom:0;padding:24px 28px 8px 28px;">
                            <p style="margin:0 0 14px 0;font-family:{{ $serif }};font-size:15px;line-height:24px;color:#2C4745;">
                                Dear Team, the sign-ins and sign-outs of the recruiters in this hour are listed below (all times IST).
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #BFD3D1;background-color:#FFFFFF;">
                                <tr>
                                    @foreach (['Time', 'Event', 'Recruiter', 'IP', 'Device'] as $h)
                                        <td style="padding:9px 10px;background-color:#F1F8F7;border-bottom:1px solid #D5E4E2;font-family:{{ $sans }};font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#0B3B3A;font-weight:bold;">{{ $h }}</td>
                                    @endforeach
                                </tr>
                                @foreach ($rows as $row)
                                    <tr>
                                        <td style="padding:8px 10px;border-bottom:1px dotted #D5E4E2;font-family:{{ $sans }};font-size:13px;color:#0B1F1E;white-space:nowrap;">{{ $row['time'] }}</td>
                                        <td style="padding:8px 10px;border-bottom:1px dotted #D5E4E2;font-family:{{ $sans }};font-size:12px;font-weight:bold;color:{{ $row['event'] === 'Logout' ? '#0B3B3A' : '#00B35A' }};">{{ strtoupper($row['event']) }}</td>
                                        <td style="padding:8px 10px;border-bottom:1px dotted #D5E4E2;font-family:{{ $serif }};font-size:14px;color:#0B1F1E;">{{ $row['name'] }}</td>
                                        <td style="padding:8px 10px;border-bottom:1px dotted #D5E4E2;font-family:{{ $sans }};font-size:12px;color:#2C4745;">{{ $row['ip'] }}</td>
                                        <td style="padding:8px 10px;border-bottom:1px dotted #D5E4E2;font-family:{{ $sans }};font-size:12px;color:#2C4745;">{{ $row['device'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td bgcolor="#FBFDFC" class="px" style="background-color:#FBFDFC;border:1px solid #BFD3D1;border-top:0;border-bottom:0;padding:18px 28px 28px 28px;">
                            <p style="margin:0;font-family:{{ $serif }};font-size:15px;line-height:24px;color:#2C4745;">
                                With kind regards,<br>
                                <span style="font-size:17px;font-style:italic;color:#0B3B3A;">Norlox Solutions</span>
                            </p>
                        </td>
                    </tr>

                    <tr><td height="4" style="height:4px;line-height:4px;font-size:0;background-color:#05A9A4;background-image:linear-gradient(90deg,#05A9A4 0%,#99E6FB 50%,#00E56F 100%);">&nbsp;</td></tr>
                    <tr>
                        <td bgcolor="#07100F" align="center" class="px" style="background-color:#07100F;padding:22px 40px;border:1px solid #07100F;">
                            <div style="font-family:{{ $serif }};font-size:14px;letter-spacing:4px;text-transform:uppercase;color:#FFFFFF;">Norlox Solutions</div>
                            <div style="font-family:{{ $serif }};font-size:12px;line-height:19px;color:#8FB5B2;padding-top:8px;">
                                This is an automated summary, one mail per hour with activity. Kindly do not reply to this message.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
