<?php

namespace App\Http\Controllers\RoleDashboards;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.admin', compact('users'));
    }
}
