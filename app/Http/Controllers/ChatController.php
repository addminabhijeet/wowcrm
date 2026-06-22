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

                $chatUser->lastChat = Chat::conversation(
                    $user->id,
                    $chatUser->id
                )
                    ->latest()
                    ->first();

                if ($chatUser->lastChat) {

                    $createdAt = $chatUser->lastChat->created_at;

                    $chatUser->lastChatDisplay = $createdAt->isToday()
                        ? $createdAt->format('h:i A')
                        : $createdAt->format('d M Y');
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
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'chatMessage' => 'nullable|string',
            'attachment' => 'nullable|file|max:51200',
        ]);

        if (
            empty($request->input('chatMessage')) &&
            !$request->hasFile('attachment')
        ) {
            return back();
        }

        $chat = new Chat();

        $chat->sender_id = Auth::id();
        $chat->receiver_id = $request->receiver_id;

        // Save text if present
        $chat->message = $request->input('chatMessage', '');

        if ($request->hasFile('attachment')) {

            $file = $request->file('attachment');

            $path = $file->store('chat-files', 'public');

            $chat->file_name = $file->getClientOriginalName();
            $chat->file_path = $path;
            $chat->file_size = $file->getSize();
            $chat->mime_type = $file->getMimeType();

            $mime = $file->getMimeType();

            if (str_starts_with($mime, 'image/')) {

                $chat->message_type = 'image';
            } elseif (str_starts_with($mime, 'video/')) {

                $chat->message_type = 'video';
            } elseif (str_starts_with($mime, 'audio/')) {

                $chat->message_type = 'audio';
            } elseif (
                $mime === 'application/pdf' ||
                strtolower($file->getClientOriginalExtension()) === 'pdf'
            ) {

                $chat->message_type = 'pdf';
            } else {

                $chat->message_type = 'file';
            }
        } else {

            $chat->message_type = 'text';
        }

        $chat->save();

        return back();
    }
}
