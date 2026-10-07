@php
    // Palette taken from the Norlox Solutions logo: ink black, emerald, teal, sky.
    $isLogout = ($event ?? 'login') === 'logout';
    $title    = $isLogout ? 'Sign-Out Notice' : 'Sign-In Notice';
    $verb     = $isLogout ? 'signed out of' : 'signed in to';
    $badgeBg  = $isLogout ? '#0B3B3A' : '#00B35A';
    $badgeTxt = $isLogout ? 'SIGNED OUT' : 'SIGNED IN';
    $serif    = "Georgia, 'Times New Roman', Times, serif";
    $sans     = "'Segoe UI', Helvetica, Arial, sans-serif";
    $rows = [
        ['Name',              $user->name],
        ['Email Address',     $user->email],
        ['User Reference',    '#' . $user->id],
        ['Date',              $whenDate],
        ['Time',              $whenTime . ' IST'],
        ['IP Address',        $ip],
        ['Browser',           $browser],
        ['Operating System',  $os],
        ['Device',            $device],
    ];
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light only">
    <title>{{ $title }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0; mso-table-rspace: 0; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        a { color: #05A9A4; }
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; }
            .px { padding-left: 20px !important; padding-right: 20px !important; }
            .logo { width: 170px !important; height: auto !important; }
            .h1 { font-size: 24px !important; line-height: 30px !important; }
            .stack { display: block !important; width: 100% !important; box-sizing: border-box; }
            .label { padding: 12px 16px 2px 16px !important; }
            .value { padding: 0 16px 12px 16px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#E9F1F0;">
    {{-- Hidden preview text shown in the inbox list --}}
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:#E9F1F0;font-size:1px;line-height:1px;">
        {{ $user->name }} has {{ $verb }} the CRM portal. Details enclosed.
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
                                <tr>
                                    {{-- Tricolour band echoing the logo gradient: emerald, sky, teal --}}
                                    <td height="8" style="height:8px;line-height:8px;font-size:0;background-color:#05A9A4;background-image:linear-gradient(90deg,#00E56F 0%,#99E6FB 50%,#05A9A4 100%);">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="center" class="px" style="padding:30px 40px 14px 40px;">
                                        <img src="{{ $logoUrl }}" alt="Norlox Solutions" class="logo" width="230" style="display:block;width:230px;max-width:100%;height:auto;margin:0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" class="px" style="padding:0 40px 26px 40px;">
                                        {{-- Fine double rule, a classic letterhead touch --}}
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr><td height="1" style="height:1px;line-height:1px;font-size:0;background-color:#05A9A4;">&nbsp;</td></tr>
                                            <tr><td height="3" style="height:3px;line-height:3px;font-size:0;">&nbsp;</td></tr>
                                            <tr><td height="1" style="height:1px;line-height:1px;font-size:0;background-color:#05A9A4;">&nbsp;</td></tr>
                                        </table>
                                        <div style="font-family:{{ $serif }};font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#4D6B69;padding-top:12px;">
                                            Customer Relationship Portal &nbsp;&bull;&nbsp; Official Notice
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Title panel --}}
                    <tr>
                        <td bgcolor="#07100F" align="center" class="px" style="background-color:#07100F;border-left:1px solid #07100F;border-right:1px solid #07100F;padding:34px 40px 30px 40px;">
                            <div style="display:inline-block;background-color:{{ $badgeBg }};color:#FFFFFF;font-family:{{ $sans }};font-size:11px;font-weight:700;letter-spacing:2.5px;padding:7px 16px;border-radius:2px;">
                                {{ $badgeTxt }}
                            </div>
                            <h1 class="h1" style="margin:16px 0 6px 0;font-family:{{ $serif }};font-size:30px;line-height:36px;font-weight:normal;color:#FFFFFF;letter-spacing:.5px;">
                                {{ $title }}
                            </h1>
                            <div style="font-family:{{ $serif }};font-size:15px;line-height:22px;font-style:italic;color:#99E6FB;">
                                {{ $user->name }} has {{ $verb }} the portal
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td height="4" style="height:4px;line-height:4px;font-size:0;background-color:#00E56F;background-image:linear-gradient(90deg,#00E56F,#05A9A4);">&nbsp;</td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td bgcolor="#FBFDFC" class="px" style="background-color:#FBFDFC;border:1px solid #BFD3D1;border-top:0;border-bottom:0;padding:34px 40px 10px 40px;">
                            <p style="margin:0 0 6px 0;font-family:{{ $serif }};font-size:17px;line-height:26px;color:#0B1F1E;">Dear Administrator,</p>
                            <p style="margin:0 0 24px 0;font-family:{{ $serif }};font-size:16px;line-height:26px;color:#2C4745;">
                                We write to inform you that a member of the team has {{ $verb }} the CRM portal.
                                The particulars of this session have been duly recorded below for your reference.
                            </p>

                            {{-- Particulars --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #BFD3D1;border-top:3px solid #05A9A4;background-color:#FFFFFF;">
                                <tr>
                                    <td colspan="2" style="padding:14px 18px;background-color:#F1F8F7;border-bottom:1px solid #D5E4E2;font-family:{{ $serif }};font-size:12px;letter-spacing:3px;text-transform:uppercase;color:#05A9A4;font-weight:bold;">
                                        Particulars of the Session
                                    </td>
                                </tr>
                                @foreach ($rows as $i => [$label, $value])
                                    <tr>
                                        <td class="stack label" width="38%" valign="top" style="padding:12px 18px;{{ $i ? 'border-top:1px solid #E3EDEC;' : '' }}font-family:{{ $sans }};font-size:12px;letter-spacing:1px;text-transform:uppercase;color:#4D6B69;">
                                            {{ $label }}
                                        </td>
                                        <td class="stack value" valign="top" style="padding:12px 18px;{{ $i ? 'border-top:1px solid #E3EDEC;' : '' }}font-family:{{ $serif }};font-size:16px;line-height:22px;color:#0B1F1E;word-break:break-word;">
                                            <strong style="font-weight:600;">{{ $value }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            {{-- Advisory --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:26px;">
                                <tr>
                                    <td width="4" bgcolor="#00E56F" style="background-color:#00E56F;font-size:0;line-height:0;">&nbsp;</td>
                                    <td bgcolor="#EEF8F5" style="background-color:#EEF8F5;padding:16px 20px;font-family:{{ $serif }};font-size:14px;line-height:22px;color:#2C4745;">
                                        <strong style="color:#0B3B3A;">A note on vigilance.</strong>
                                        Should this activity appear unfamiliar, kindly review the account and contact the system administrator without delay.
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:30px 0 0 0;font-family:{{ $serif }};font-size:15px;line-height:24px;color:#2C4745;">
                                With kind regards,<br>
                                <span style="font-size:17px;font-style:italic;color:#0B3B3A;">Norlox Solutions</span><br>
                                <span style="font-family:{{ $sans }};font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#4D6B69;">Security &amp; Records</span>
                            </p>
                        </td>
                    </tr>

                    {{-- Technical record --}}
                    <tr>
                        <td bgcolor="#FBFDFC" class="px" style="background-color:#FBFDFC;border:1px solid #BFD3D1;border-top:0;border-bottom:0;padding:22px 40px 34px 40px;">
                            <div style="font-family:{{ $sans }};font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#7C9391;padding-bottom:6px;">Browser Signature</div>
                            <div style="font-family:Consolas, 'Courier New', monospace;font-size:11px;line-height:17px;color:#5C7573;word-break:break-all;">{{ $userAgent ?: '-' }}</div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td height="4" style="height:4px;line-height:4px;font-size:0;background-color:#05A9A4;background-image:linear-gradient(90deg,#05A9A4 0%,#99E6FB 50%,#00E56F 100%);">&nbsp;</td>
                    </tr>
                    <tr>
                        <td bgcolor="#07100F" align="center" class="px" style="background-color:#07100F;padding:26px 40px;border:1px solid #07100F;">
                            <div style="font-family:{{ $serif }};font-size:14px;letter-spacing:4px;text-transform:uppercase;color:#FFFFFF;">Norlox Solutions</div>
                            <div style="font-family:{{ $serif }};font-size:12px;line-height:19px;color:#8FB5B2;padding-top:10px;">
                                This is an automated notice, issued in confidence to authorised recipients only.<br>
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
