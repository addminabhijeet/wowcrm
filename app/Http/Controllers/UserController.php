<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;


class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('user.admin', compact('users'));
    }

    public function junior()
    {
        $users = User::all();
        return view('user.junior', compact('users'));
    }

    public function senior()
    {
        $users = User::all();
        return view('user.senior', compact('users'));
    }

    public function trainer()
    {
        $users = User::all();
        return view('user.trainer', compact('users'));
    }

    public function accountant()
    {
        $users = User::all();
        return view('user.accountant', compact('users'));
    }

    public function customer()
    {
        $users = User::all();
        return view('user.customer', compact('users'));
    }
}
