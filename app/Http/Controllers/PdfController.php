<?php

namespace App\Http\Controllers;

use App\Models\GoogleSheetData;
use Illuminate\Http\Request;

class PdfController extends Controller
{

    public function acceptance(Request $request, $id)
    {
        // Fetch row by ID
        $record = GoogleSheetData::findOrFail($id);

        // Map database fields to the variables used in the PDF view
        $name   = $record->Name;
        $email  = $record->Email_Address;
        $amount = $record->Amount;
        $course = $record->Course;
        $remark = $record->Remark;

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
