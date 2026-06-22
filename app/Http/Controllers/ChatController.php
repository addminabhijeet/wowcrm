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

    public function junior(Request $request)
    {
        $user = Auth::user();

        $users = User::whereIn('role', ['junior', 'senior'])
            ->where('is_deleted', 0)
            ->where('id', '!=', $user->id)
            ->get()
            ->map(function ($chatUser) use ($user) {

                $chatUser->lastChat = Chat::conversation($user->id, $chatUser->id)
                    ->latest()
                    ->first();

                return $chatUser;
            });

        return view('chat.junior', compact('users'));
    }
}
