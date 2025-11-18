<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function acceptance(Request $request)
    {
        $name   = $request->input('name');
        $email  = $request->input('email');
        $amount = $request->input('amount');
        $course = $request->input('course');
        $remark = $request->input('remark');

        return view('pdf.acceptance', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark'
        ));
    }

    public function consultation(Request $request)
    {
        $name   = $request->input('name');
        $email  = $request->input('email');
        $amount = $request->input('amount');
        $course = $request->input('course');
        $remark = $request->input('remark');

        return view('pdf.consultation', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark'
        ));
    }

    public function delivery(Request $request)
    {
        $name   = $request->input('name');
        $email  = $request->input('email');
        $amount = $request->input('amount');
        $course = $request->input('course');
        $remark = $request->input('remark');

        return view('pdf.delivery', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark'
        ));
    }

    public function payment(Request $request)
    {
        $name   = $request->input('name');
        $email  = $request->input('email');
        $amount = $request->input('amount');
        $course = $request->input('course');
        $remark = $request->input('remark');

        return view('pdf.payment', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark'
        ));
    }

    public function deliveryuk(Request $request)
    {
        $name   = $request->input('name');
        $email  = $request->input('email');
        $amount = $request->input('amount');
        $course = $request->input('course');
        $remark = $request->input('remark');

        return view('pdf.deliveryuk', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark'
        ));
    }

    public function paymentuk(Request $request)
    {
        $name   = $request->input('name');
        $email  = $request->input('email');
        $amount = $request->input('amount');
        $course = $request->input('course');
        $remark = $request->input('remark');

        return view('pdf.paymentuk', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark'
        ));
    }
}
