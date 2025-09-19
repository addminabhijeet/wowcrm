<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logins; // model for logins table

class LoginsController extends Controller
{
    public function index()
    {
        $logins = Logins::with('user')->latest()->get(); // eager load user for user_id
        return view('admin.logins', compact('logins'));
    }
}
