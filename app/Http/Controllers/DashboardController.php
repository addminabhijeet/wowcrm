<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'junior':
                return redirect()->route('dashboard.junior');
            case 'senior':
                return redirect()->route('dashboard.senior');
            case 'customer':
                return redirect()->route('dashboard.customer');
            case 'accountant':
                return redirect()->route('dashboard.accountant');
            case 'trainer':
                return redirect()->route('dashboard.trainer');
            case 'admin':
                return redirect()->route('dashboard.admin');
            default:
                abort(403, 'Unauthorized action.');
        }
    }

}
