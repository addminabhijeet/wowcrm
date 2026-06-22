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
        $selectedUserId = $request->user;

        $users = User::whereIn('role', ['junior', 'senior'])
            ->where('is_deleted', 0)
            ->where('id', '!=', $user->id)
            ->get()
            ->map(function ($chatUser) use ($user) {

                $chatUser->lastChat = Chat::conversation($user->id, $chatUser->id)
                    ->latest('created_at')
                    ->latest('id')
                    ->first();

                $chatUser->lastChatDate = $chatUser->lastChat
                    ? $chatUser->lastChat->created_at->format('d M Y')
                    : '';

                $chatUser->lastChatTime = $chatUser->lastChat
                    ? $chatUser->lastChat->created_at->format('h:i A')
                    : '';

                return $chatUser;
            });

        $activeUser = $users
            ->where('id', $selectedUserId)
            ->first() ?? $users->first();

        $messages = collect();

        if ($activeUser) {
            $messages = Chat::conversation(
                $user->id,
                $activeUser->id
            )->get();
        }

        return view('chat.junior', compact(
            'users',
            'activeUser',
            'messages'
        ));
    }
}
