<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $template = EmailTemplate::firstOrCreate(
            ['name' => 'Called_Mailed'],
            [
                'subject' => 'Course & Amount Information',
                'body' => "Hello,<br><br>Your course: {{course}}<br>Amount: {{amount}}<br><br>Thank you for your interest.<br><br>Regards,<br>{{from_name}}"
            ]
        );
        return view('smtp.edittemplate', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $template = EmailTemplate::findOrFail($id);
        $template->update($request->only('subject', 'body'));

        return redirect()->back()->with('success', 'Template updated successfully!');
    }

    public function renderTemplate($slug, $data = [])
    {
        $template = EmailTemplate::where('name', $slug)->first();
        if (!$template) {
            return null;
        }

        // Replace variables directly in the body
        $body = $template->body;
        foreach ($data as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }

        return [
            'subject' => $template->subject,
            'body' => $body
        ];
    }
}
