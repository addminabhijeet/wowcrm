<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{

    // Display chat page
    public function junior(Request $request)
    {
        $user = Auth::user();
        $users = User::where('id', '!=', $user->id)->get();

        return view('chat.junior', compact('users'));
    }
}
