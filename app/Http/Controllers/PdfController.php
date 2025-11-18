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


    public function consultation()
    {
        return view('pdf.consultation');
    }

    public function delivery()
    {
        return view('pdf.delivery');
    }

    public function payment()
    {
        return view('pdf.payment');
    }

    public function deliveryuk()
    {
        return view('pdf.deliveryuk');
    }

    public function paymentuk()
    {
        return view('pdf.paymentuk');
    }
}
