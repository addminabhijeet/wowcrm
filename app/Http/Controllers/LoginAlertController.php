<?php

namespace App\Http\Controllers;

use App\Services\LoginAlertService;
use Illuminate\Http\Request;

class LoginAlertController extends Controller
{
    public function index()
    {
        abort_unless(in_array(auth()->user()->role ?? '', ['admin', 'operation'], true), 403);

        return view('smtp.editalllogin', ['recipients' => LoginAlertService::recipients()]);
    }

    /** Added: switch between grouped (one mail per hour, juniors only) and one mail per login / logout. */
    public function setDigestMode(Request $request)
    {
        abort_unless(in_array(auth()->user()->role ?? '', ['admin', 'operation'], true), 403);

        $request->validate(['grouped' => ['required', 'boolean']]);
        \App\Services\LoginDigestMail::setGrouped($request->boolean('grouped'));

        return redirect()->route('smtp.editalllogin')->with('success', $request->boolean('grouped')
            ? 'Grouped login / logout mails are on.' : 'One mail per login / logout is on again.');
    }

    public function update(Request $request)
    {
        abort_unless(in_array(auth()->user()->role ?? '', ['admin', 'operation'], true), 403);

        $request->validate([
            'emails' => ['nullable', 'string', 'max:1000'],
            'cc'     => ['nullable', 'string', 'max:1000'],
        ]);

        $errors = [];
        $parsed = [];
        foreach (['emails' => 'To', 'cc' => 'CC'] as $field => $label) {
            $list = collect(preg_split('/[\s,;]+/', (string) $request->input($field), -1, PREG_SPLIT_NO_EMPTY))
                ->map(fn ($e) => strtolower(trim($e)));
            $invalid = $list->reject(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL));
            if ($invalid->isNotEmpty()) {
                $errors[$field] = "Invalid email in {$label}: " . $invalid->implode(', ');
            }
            $parsed[$field] = $list->all();
        }

        if ($errors) {
            return back()->withInput()->withErrors($errors);
        }

        LoginAlertService::saveRecipients($parsed['emails'], $parsed['cc']);

        return redirect()->route('smtp.editalllogin')->with('success', 'Login mail recipients saved.');
    }
}
