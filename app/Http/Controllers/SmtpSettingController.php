<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\SmtpSetting;



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
        $smtp = SmtpSetting::first();

        if (!$smtp) {
            return response()->json([
                'status' => 'error',
                'message' => 'No SMTP settings found.'
            ]);
        }

        // Configure SMTP dynamically
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => $smtp->mailer,
            'mail.mailers.smtp.host' => $smtp->host,
            'mail.mailers.smtp.port' => $smtp->port,
            'mail.mailers.smtp.username' => $smtp->username,
            'mail.mailers.smtp.password' => decrypt($smtp->password),
            'mail.mailers.smtp.encryption' => $smtp->encryption,
            'mail.from.address' => $smtp->from_address,
            'mail.from.name' => $smtp->from_name,
        ]);

        $testEmail = $request->input('test_email');

        try {
            Mail::send([], [], function ($message) use ($testEmail, $smtp) {
                $htmlContent = '<p>This is a test email from <strong>'
                    . e($smtp->from_name) . '</strong> ('
                    . e($smtp->from_address) . ').</p>';

                // Use string HTML body (Laravel will set correct MIME)
                $message->to($testEmail)
                    ->subject('SMTP Test Email')
                    ->html($htmlContent); // <- uses string only
            });

            return response()->json([
                'status' => 'success',
                'message' => "✅ Test email sent successfully to {$testEmail}!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '❌ Failed to send test email: ' . $e->getMessage()
            ]);
        }
    }
}
