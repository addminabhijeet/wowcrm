<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin')->get();
        return view('user.admin', compact('users'));
    }

    public function junior()
    {
        $users = User::where('role', 'junior')->get();
        return view('user.junior', compact('users'));
    }

    public function senior()
    {
        $users = User::where('role', 'senior')->get();
        return view('user.senior', compact('users'));
    }

    public function trainer()
    {
        $users = User::where('role', 'trainer')->get();
        return view('user.trainer', compact('users'));
    }

    public function accountant()
    {
        $users = User::where('role', 'accountant')->get();
        return view('user.accountant', compact('users'));
    }

    public function customer()
    {
        $users = User::where('role', 'customer')->get();
        return view('user.customer', compact('users'));
    }
}