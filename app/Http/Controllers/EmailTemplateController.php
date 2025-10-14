<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        // Fetch the template if it exists, but do not auto-fill
        $template = EmailTemplate::where('name', 'Called_Mailed')->first();

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

}
