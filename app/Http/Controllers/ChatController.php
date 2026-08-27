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

        // ✅ COMPRESSION: Select only needed columns to reduce RAM
        $users = User::whereIn('role', ['junior', 'senior'])
            ->where('is_deleted', 0)
            ->where('id', '!=', $user->id)
            ->select(['id', 'name', 'email', 'phone', 'gender', 'role', 'group', 'target', 'target_date', 'image'])
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

        // ✅ COMPRESSION: Select only needed columns + use UNION for efficiency
        $unreadCounts = Chat::whereIn('sender_id', $users->pluck('id'))
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->get(['sender_id', 'unread_count'])
            ->keyBy('sender_id');

        // ✅ COMPRESSION: Select only needed columns + limit to latest only
        $userIds = $users->pluck('id')->toArray();
        $lastMessages = Chat::whereIn('sender_id', $userIds)
            ->where('receiver_id', $user->id)
            ->select(['id', 'sender_id', 'receiver_id', 'created_at'])
            ->orderBy('sender_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('sender_id')
            ->keyBy('sender_id');

        // ✅ COMPRESSION: Add unread from opposite direction
        if (!empty($userIds)) {
            $reverseMessages = Chat::whereIn('receiver_id', $userIds)
                ->where('sender_id', $user->id)
                ->select(['id', 'sender_id', 'receiver_id', 'created_at'])
                ->orderBy('receiver_id')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('receiver_id');

            foreach ($reverseMessages as $msg) {
                if (!$lastMessages->has($msg->receiver_id)) {
                    $lastMessages[$msg->receiver_id] = $msg;
                }
            }
        }

        $users = $users->map(function ($chatUser) use ($user, $unreadCounts, $lastMessages) {
            $lastMsg = $lastMessages->get($chatUser->id);

            $chatUser->lastChat = $lastMsg;
            $chatUser->unreadCount = $unreadCounts->get($chatUser->id)->unread_count ?? 0;

            if ($lastMsg) {
                $createdAt = $lastMsg->created_at;
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

        // ✅ COMPRESSION: Select needed columns (keeping User properties for frontend compatibility)
        $chatUsers = User::whereIn('role', ['junior', 'senior'])
            ->where('is_deleted', 0)
            ->where('id', '!=', $user->id)
            ->select(['id', 'name', 'email', 'phone', 'image', 'role', 'gender', 'group'])
            ->get()
            ->keyBy('id');

        // ✅ COMPRESSION: Get unread counts in single optimized query
        $userIds = $chatUsers->keys()->toArray();
        $unreadCounts = Chat::whereIn('sender_id', $userIds)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->get(['sender_id', 'unread_count'])
            ->keyBy('sender_id');

        // ✅ COMPRESSION: Only fetch messages with unread counts
        $userIdsWithUnread = $unreadCounts->keys()->toArray();

        if (empty($userIdsWithUnread)) {
            return response()->json(['count' => 0, 'users' => []]);
        }

        $lastMessages = Chat::whereIn('sender_id', $userIdsWithUnread)
            ->where('receiver_id', $user->id)
            ->select(['id', 'sender_id', 'receiver_id', 'created_at'])
            ->orderBy('sender_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('sender_id')
            ->keyBy('sender_id');

        // ✅ COMPRESSION: Efficient O(1) lookup using pre-keyed collection
        $result = [];
        $totalUnread = 0;

        foreach ($userIdsWithUnread as $userId) {
            $unreadCount = $unreadCounts->get($userId)->unread_count ?? 0;
            $totalUnread += $unreadCount;

            // ✅ O(1) lookup instead of O(n) firstWhere()
            $chatUser = $chatUsers->get($userId);
            if ($chatUser) {
                $chatUser->unreadCount = $unreadCount;
                $chatUser->lastChat = $lastMessages->get($userId);
                $result[] = $chatUser;
            }
        }

        // ✅ COMPRESSION: Sort efficiently in memory
        usort($result, function ($a, $b) {
            $timeA = $a->lastChat?->created_at ?? now()->subYear();
            $timeB = $b->lastChat?->created_at ?? now()->subYear();
            return $timeB->timestamp <=> $timeA->timestamp;
        });

        return response()->json(['count' => $totalUnread, 'users' => $result]);
    }

    public function refreshChatUsers(Request $request)
    {
        $user = Auth::user();

        // ✅ COMPRESSION: Select only needed columns
        $chatUsers = User::whereIn('role', ['junior', 'senior'])
            ->where('is_deleted', 0)
            ->where('id', '!=', $user->id)
            ->select(['id', 'name', 'image'])
            ->get();

        $userIds = $chatUsers->pluck('id')->toArray();

        // ✅ COMPRESSION: Get unread counts in single query
        $unreadCounts = Chat::whereIn('sender_id', $userIds)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->get(['sender_id', 'unread_count'])
            ->keyBy('sender_id');

        // ✅ COMPRESSION: Fetch last messages with minimal columns
        $lastMessages = Chat::whereIn('sender_id', $userIds)
            ->where('receiver_id', $user->id)
            ->select(['id', 'sender_id', 'receiver_id', 'created_at'])
            ->orderBy('sender_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('sender_id')
            ->keyBy('sender_id');

        // ✅ COMPRESSION: Add reverse direction messages efficiently
        if (!empty($userIds)) {
            $reverseMessages = Chat::whereIn('receiver_id', $userIds)
                ->where('sender_id', $user->id)
                ->select(['id', 'sender_id', 'receiver_id', 'created_at'])
                ->orderBy('receiver_id')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('receiver_id');

            foreach ($reverseMessages as $msg) {
                if (!$lastMessages->has($msg->receiver_id)) {
                    $lastMessages[$msg->receiver_id] = $msg;
                }
            }
        }

        $chatUsersFormatted = [];
        foreach ($chatUsers as $chatUser) {
            $lastMsg = $lastMessages->get($chatUser->id);
            $chatUser->lastChat = $lastMsg;
            $chatUser->unreadCount = $unreadCounts->get($chatUser->id)->unread_count ?? 0;

            if ($lastMsg) {
                $createdAt = $lastMsg->created_at;
                $chatUser->lastChatDisplay = $createdAt->isToday()
                    ? $createdAt->format('h:i A')
                    : $createdAt->format('d M Y');
            } else {
                $chatUser->lastChatDisplay = '';
            }

            $chatUsersFormatted[] = $chatUser;
        }

        // ✅ COMPRESSION: Sort in memory instead of collection chains
        usort($chatUsersFormatted, function ($a, $b) {
            $timeA = $a->lastChat?->created_at ?? now()->subYear();
            $timeB = $b->lastChat?->created_at ?? now()->subYear();
            return $timeB->timestamp <=> $timeA->timestamp;
        });

        $chatUsers = collect($chatUsersFormatted)->values();

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
