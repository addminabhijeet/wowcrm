<?php

namespace App\Http\Controllers\RoleDashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class AccountantDashboardController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['customer', 'resume'])->get();
        return view('dashboard.accountant', compact('payments'));
    }
}
