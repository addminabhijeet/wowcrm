<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\SmtpSetting;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SmtpSettingController extends Controller
{
    // Show the form to edit SMTP settings
    public function edit()
    {
        $smtp = SmtpSetting::first(); // Assume single record
        return view('smtp_settings.edit', compact('smtp'));
    }

    // Update SMTP settings
    public function update(Request $request)
    {
        $request->validate([
            'mailer' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|email',
            'password' => 'nullable|string',
            'encryption' => 'required|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
        ]);

        $smtp = SmtpSetting::first();
        if (!$smtp) {
            $smtp = new SmtpSetting();
        }

        $smtp->mailer = $request->mailer;
        $smtp->host = $request->host;
        $smtp->port = $request->port;
        $smtp->username = $request->username;
        if ($request->filled('password')) {
            $smtp->password = encrypt($request->password); // encrypt password
        }
        $smtp->encryption = $request->encryption;
        $smtp->from_address = $request->from_address;
        $smtp->from_name = $request->from_name;

        $smtp->save();

        return redirect()->back()->with('success', 'SMTP settings updated successfully!');
    }

    public function test(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $smtp = SmtpSetting::first();
        if (!$smtp) {
            return response()->json(['message' => 'No SMTP settings found.'], 400);
        }

        // Determine encryption
        $encryption = strtolower($smtp->encryption);
        if ($encryption === 'ssl/tls') $encryption = 'ssl';
        if (!in_array($encryption, ['ssl', 'tls', null])) $encryption = null;

        // Test SMTP connection
        try {
            $transport = new EsmtpTransport(
                $smtp->host,
                $smtp->port,
                $encryption === 'ssl' // $encryption argument expects bool for SSL
            );

            $transport->setUsername($smtp->username);
            $transport->setPassword(decrypt($smtp->password));

            // start() will attempt connection
            $transport->start();
        } catch (TransportExceptionInterface $e) {
            return response()->json([
                'message' => 'SMTP connection failed. Check host, port, encryption, or firewall.',
                'error' => $e->getMessage()
            ], 500);
        }

        // Override Laravel mail config
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $smtp->host,
            'mail.mailers.smtp.port' => $smtp->port,
            'mail.mailers.smtp.username' => $smtp->username,
            'mail.mailers.smtp.password' => decrypt($smtp->password),
            'mail.mailers.smtp.encryption' => $encryption === 'ssl' ? 'ssl' : 'tls',
            'mail.mailers.smtp.auth_mode' => 'login',
            'mail.from.address' => $smtp->from_address,
            'mail.from.name' => $smtp->from_name,
            'mail.mailers.smtp.timeout' => 30,
        ]);

        $testEmail = $request->test_email;

        try {
            Mail::raw('This is a test email from Synergie Systems CRM.', function ($message) use ($testEmail) {
                $message->to($testEmail)->subject('SMTP Test Email');
            });

            return response()->json([
                'message' => "Test email sent successfully to {$testEmail}!"
            ]);
        } catch (TransportExceptionInterface $e) {
            return response()->json([
                'message' => 'SMTP Transport Error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send test email. Check credentials and server restrictions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
