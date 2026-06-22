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
        $search = $request->search;

        $users = User::whereIn('role', ['junior', 'senior'])
            ->where('is_deleted', 0)
            ->where('id', '!=', $user->id)
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('gender', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('group', 'like', "%{$search}%")
                        ->orWhere('target', 'like', "%{$search}%")
                        ->orWhere('target_date', 'like', "%{$search}%");
                });
            })
            ->get()
            ->map(function ($chatUser) use ($user) {

                $chatUser->lastChat = Chat::conversation($user->id, $chatUser->id)
                    ->latest('created_at')
                    ->latest('id')
                    ->first();

                if ($chatUser->lastChat) {

                    $createdAt = $chatUser->lastChat->created_at;

                    $chatUser->lastChatDisplay = $createdAt->isToday()
                        ? $createdAt->format('h:i A')
                        : $createdAt->format('d M Y');
                } else {

                    $chatUser->lastChatDisplay = '';
                }

                if ($chatUser->lastChat) {

                    if ($chatUser->lastChat->created_at->isToday()) {

                        // Today → show time
                        $chatUser->lastChatDisplay =
                            $chatUser->lastChat->created_at->format('h:i A');
                    } else {

                        // Previous days → show date
                        $chatUser->lastChatDisplay =
                            $chatUser->lastChat->created_at->format('d M Y');
                    }
                } else {

                    $chatUser->lastChatDisplay = '';
                }

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
