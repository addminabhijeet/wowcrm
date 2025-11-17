<?php

namespace App\Http\Controllers;

class PdfController extends Controller
{
    public function acceptance()
    {
        return view('pdf.acceptance');
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
