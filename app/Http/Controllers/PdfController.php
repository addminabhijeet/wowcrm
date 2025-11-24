<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function acceptance(Request $request)
    {
        $name          = $request->input('name');
        $email         = $request->input('email');
        $amount        = $request->input('amount');
        $course        = $request->input('course');
        $remark        = $request->input('remark');
        $tranId        = $request->input('tranId');
        $tranRef       = $request->input('tranRef');
        $paymentDate = $request->input('paymentDate');
        $paymentMethod = $request->input('paymentMethod');
        $payeeName     = $request->input('payeeName');

        return view('pdf.acceptance', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark',
            'tranId',
            'tranRef',
            'paymentDate',
            'paymentMethod',
            'payeeName'
        ));
    }


    public function consultation(Request $request)
    {
        $name          = $request->input('name');
        $email         = $request->input('email');
        $amount        = $request->input('amount');
        $course        = $request->input('course');
        $remark        = $request->input('remark');
        $tranId        = $request->input('tranId');
        $tranRef       = $request->input('tranRef');
        $paymentDate = $request->input('paymentDate');
        $paymentMethod = $request->input('paymentMethod');
        $payeeName     = $request->input('payeeName');

        return view('pdf.consultation', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark',
            'tranId',
            'tranRef',
            'paymentDate',
            'paymentMethod',
            'payeeName'
        ));
    }

    public function delivery(Request $request)
    {
        $name          = $request->input('name');
        $email         = $request->input('email');
        $amount        = $request->input('amount');
        $course        = $request->input('course');
        $remark        = $request->input('remark');
        $tranId        = $request->input('tranId');
        $tranRef       = $request->input('tranRef');
        $paymentDate = $request->input('paymentDate');
        $paymentMethod = $request->input('paymentMethod');
        $payeeName     = $request->input('payeeName');

        return view('pdf.delivery', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark',
            'tranId',
            'tranRef',
            'paymentDate',
            'paymentMethod',
            'payeeName'
        ));
    }

    public function payment(Request $request)
    {
        $name          = $request->input('name');
        $email         = $request->input('email');
        $amount        = $request->input('amount');
        $course        = $request->input('course');
        $remark        = $request->input('remark');
        $tranId        = $request->input('tranId');
        $tranRef       = $request->input('tranRef');
        $paymentDate = $request->input('paymentDate');
        $paymentMethod = $request->input('paymentMethod');
        $payeeName     = $request->input('payeeName');

        return view('pdf.payment', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark',
            'tranId',
            'tranRef',
            'paymentDate',
            'paymentMethod',
            'payeeName'
        ));
    }

    public function deliveryuk(Request $request)
    {
        $name          = $request->input('name');
        $email         = $request->input('email');
        $amount        = $request->input('amount');
        $course        = $request->input('course');
        $remark        = $request->input('remark');
        $tranId        = $request->input('tranId');
        $tranRef       = $request->input('tranRef');
        $paymentDate = $request->input('paymentDate');
        $paymentMethod = $request->input('paymentMethod');
        $payeeName     = $request->input('payeeName');

        return view('pdf.deliveryuk', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark',
            'tranId',
            'tranRef',
            'paymentDate',
            'paymentMethod',
            'payeeName'
        ));
    }

    public function paymentuk(Request $request)
    {
        $name          = $request->input('name');
        $email         = $request->input('email');
        $amount        = $request->input('amount');
        $course        = $request->input('course');
        $remark        = $request->input('remark');
        $tranId        = $request->input('tranId');
        $tranRef       = $request->input('tranRef');
        $paymentDate = $request->input('paymentDate');
        $paymentMethod = $request->input('paymentMethod');
        $payeeName     = $request->input('payeeName');

        return view('pdf.paymentuk', compact(
            'name',
            'email',
            'amount',
            'course',
            'remark',
            'tranId',
            'tranRef',
            'paymentDate',
            'paymentMethod',
            'payeeName'
        ));
    }
}
