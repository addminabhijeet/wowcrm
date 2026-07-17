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
            ->get();

        // Fetch all chat data at once to avoid N+1 queries
        $userIds = $users->pluck('id')->toArray();
        $lastChats = Chat::whereIn('sender_id', array_merge([$user->id], $userIds))
            ->whereIn('receiver_id', array_merge([$user->id], $userIds))
            ->get()
            ->groupBy(function ($chat) use ($user) {
                $otherId = $chat->sender_id == $user->id ? $chat->receiver_id : $chat->sender_id;
                return $otherId;
            })
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->first();
            });

        $unreadCounts = Chat::whereIn('sender_id', $userIds)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->groupBy('sender_id')
            ->selectRaw('sender_id, count(*) as count')
            ->pluck('count', 'sender_id');

        $users = $users->map(function ($chatUser) use ($user, $lastChats, $unreadCounts) {
                $chatUser->lastChat = $lastChats->get($chatUser->id);
                $chatUser->unreadCount = $unreadCounts->get($chatUser->id, 0);

                if ($chatUser->lastChat) {

                    $createdAt = $chatUser->lastChat->created_at;

                    $chatUser->lastChatDisplay = $createdAt->isToday()
                        ? $createdAt->format('h:i A')
                        : $createdAt->format('d M Y');
                } else {

                    $chatUser->lastChatDisplay = '';
                }

                return $chatUser;
            })->sortByDesc(function ($chatUser) {

                return optional($chatUser->lastChat)->created_at;
            })
            ->values();

        $activeUser = $users
            ->where('id', $selectedUserId)
            ->first() ?? $users->first();
        $messages = collect();

        if ($activeUser) {

            // Mark all unread messages from this user as read
            Chat::where('sender_id', $activeUser->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            $messages = Chat::conversation(
                $user->id,
                $activeUser->id
            )
                ->whereNull('parent_id')
                ->with([
                    'replies' => function ($q) {
                        $q->orderBy('id', 'asc');
                    }
                ])
                ->get();
        }

        return view('chat.junior', compact(
            'users',
            'activeUser',
            'messages'
        ));
    }

    public function send(Request $request)
    {
        // Main message
        $parentChat = new Chat();
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'attachment.*' => 'nullable|file|max:10240',
            'image_attachment.*' => 'nullable|image|max:10240',
        ]);

        $parentChat->sender_id = Auth::id();
        $parentChat->receiver_id = $request->receiver_id;
        $parentChat->message = $request->input('chatMessage', '');
        $parentChat->message_type = 'text';

        if (
            !empty(trim(strip_tags($request->chatMessage))) ||
            $request->hasFile('attachment') ||
            $request->hasFile('image_attachment')
        ) {

            $parentChat->save();
        }

        $allFiles = [];

        if ($request->hasFile('attachment')) {

            foreach ((array)$request->file('attachment') as $file) {

                if ($file) {
                    $allFiles[] = $file;
                }
            }
        }

        if ($request->hasFile('image_attachment')) {

            foreach ((array)$request->file('image_attachment') as $file) {

                if ($file) {
                    $allFiles[] = $file;
                }
            }
        }

        foreach ($allFiles as $file) {

            $path = $file->store('chat-files', 'public');

            $chat = new Chat();

            $chat->sender_id = Auth::id();
            $chat->receiver_id = $request->receiver_id;

            $chat->parent_id = $parentChat->exists ? $parentChat->id : null;

            $chat->file_name = $file->getClientOriginalName();
            $chat->file_path = $path;
            $chat->file_size = $file->getSize();
            $chat->mime_type = $file->getMimeType();

            $mime = $file->getMimeType();

            if (str_starts_with($mime, 'image/')) {

                $chat->message_type = 'image';
            } elseif (
                str_contains(strtolower($mime), 'pdf')
                || strtolower($file->getClientOriginalExtension()) == 'pdf'
            ) {

                $chat->message_type = 'pdf';
            } else {

                $chat->message_type = 'file';
            }

            $chat->save();
        }

        return back();
    }

    public function latestMessages()
    {
        $user = Auth::user();

        $chatUsers = User::whereIn('role', ['junior', 'senior'])
            ->where('is_deleted', 0)
            ->where('id', '!=', $user->id)
            ->get()
            ->map(function ($chatUser) use ($user) {

                $chatUser->lastChat = Chat::conversation(
                    $user->id,
                    $chatUser->id
                )
                    ->reorder('created_at', 'desc')
                    ->first();

                $chatUser->unreadCount = Chat::where('sender_id', $chatUser->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                return $chatUser;
            })
            ->filter(function ($chatUser) {
                return $chatUser->unreadCount > 0;
            })
            ->sortByDesc(function ($chatUser) {
                return optional($chatUser->lastChat)->created_at;
            })
            ->values();

        return response()->json([
            'count' => $chatUsers->sum('unreadCount'),
            'users' => $chatUsers,
        ]);
    }

    public function refreshChatUsers(Request $request)
    {
        $user = Auth::user();

        // Refresh all users
        $chatUsers = User::whereIn('role', ['junior', 'senior'])
            ->where('is_deleted', 0)
            ->where('id', '!=', $user->id)
            ->get()
            ->map(function ($chatUser) use ($user) {

                $chatUser->lastChat = Chat::conversation(
                    $user->id,
                    $chatUser->id
                )
                    ->reorder('created_at', 'desc')
                    ->first();

                $chatUser->unreadCount = Chat::where('sender_id', $chatUser->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                if ($chatUser->lastChat) {

                    $createdAt = $chatUser->lastChat->created_at;

                    $chatUser->lastChatDisplay = $createdAt->isToday()
                        ? $createdAt->format('h:i A')
                        : $createdAt->format('d M Y');
                } else {

                    $chatUser->lastChatDisplay = '';
                }

                return $chatUser;
            })
            ->sortByDesc(function ($chatUser) {

                return optional($chatUser->lastChat)->created_at;
            })
            ->values();

        // Active user from Blade
        $activeUser = null;

        if (!empty($request->active_user_id)) {

            $activeUser = User::where('id', $request->active_user_id)
                ->where('is_deleted', 0)
                ->first();
        }

        // Default to first user if active user not found
        if (!$activeUser) {

            $activeUser = $chatUsers->first();
        }

        $messages = collect();

        if ($activeUser) {

            // Mark unread messages from active user as read
            Chat::where('sender_id', $activeUser->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            // Refresh conversation
            $messages = Chat::conversation(
                $user->id,
                $activeUser->id
            )
                ->whereNull('parent_id')
                ->with([
                    'replies' => function ($q) {

                        $q->orderBy('id', 'asc');
                    }
                ])
                ->get();
        }

        return response()->json([
            'users' => $chatUsers,

            'activeUser' => [
                'id' => $activeUser?->id,
                'name' => $activeUser?->name,
                'image' => $activeUser?->image,
            ],

            'messages' => $messages,

            'lastMessageId' => $messages->last()?->id,
        ]);
    }
}
