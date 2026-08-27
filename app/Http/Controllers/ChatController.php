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
        $userId = $user->id;
        $selectedUserId = $request->user;
        $search = $request->search;

        // ✅ EXTREME-COMPRESSION: Single raw SQL JOIN (all data in 1 query + LIMIT)
        $searchWhere = '';
        $params = [$userId, $userId];

        if ($search) {
            $searchWhere = " AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $result = \DB::select("
            SELECT u.id, u.name, u.image, u.email, u.phone, u.role, u.gender, u.group, u.target, u.target_date,
                   COALESCE(SUM(CASE WHEN c.is_read = 0 THEN 1 ELSE 0 END), 0) as unreadCount,
                   MAX(c.created_at) as lastChatTime
            FROM users u
            LEFT JOIN chats c ON c.sender_id = u.id AND c.receiver_id = ?
            WHERE u.role IN ('junior', 'senior') AND u.is_deleted = 0 AND u.id != ? {$searchWhere}
            GROUP BY u.id, u.name, u.image, u.email, u.phone, u.role, u.gender, u.group, u.target, u.target_date
            ORDER BY MAX(c.created_at) DESC
            LIMIT 200
        ", $params);

        if (empty($result)) {
            return view('chat.junior', ['users' => [], 'activeUser' => null, 'messages' => collect(), 'selectedUserId' => $selectedUserId]);
        }

        // ✅ EXTREME-COMPRESSION: Convert to objects + attach minimal chat data
        $users = collect($result)->map(function ($row) {
            $row->unreadCount = (int)$row->unreadCount;
            $row->lastChat = $row->lastChatTime ? (object)['created_at' => $row->lastChatTime] : null;

            // ✅ Format display time for view
            if ($row->lastChatTime) {
                $time = strtotime($row->lastChatTime);
                $row->lastChatDisplay = date('Y-m-d') === date('Y-m-d', $time)
                    ? date('h:i A', $time)
                    : date('d M Y', $time);
            } else {
                $row->lastChatDisplay = '';
            }
            unset($row->lastChatTime);
            return $row;
        });

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
        $userId = $user->id;

        // ✅ EXTREME-COMPRESSION: Single raw query for unread + last message timestamp
        $rawData = \DB::select("
            SELECT DISTINCT c1.sender_id as user_id,
                   COUNT(CASE WHEN c1.is_read = 0 THEN 1 END) as cnt,
                   MAX(c1.created_at) as last_time
            FROM chats c1
            WHERE c1.receiver_id = ?
              AND c1.sender_id IN (
                SELECT id FROM users WHERE role IN ('junior', 'senior')
                AND is_deleted = 0 AND id != ?
              )
            GROUP BY c1.sender_id
            ORDER BY MAX(c1.created_at) DESC
            LIMIT 100
        ", [$userId, $userId]);

        if (empty($rawData)) {
            return response()->json(['count' => 0, 'users' => []]);
        }

        $userIds = array_column($rawData, 'user_id');

        // ✅ EXTREME-COMPRESSION: Fetch users in bulk (minimal columns)
        $users = User::whereIn('id', $userIds)
            ->select(['id', 'name', 'image'])
            ->get()
            ->all();

        // ✅ EXTREME-COMPRESSION: Build result using raw query data (no extra queries)
        $result = [];
        $totalUnread = 0;
        $userMap = [];

        foreach ($users as $u) {
            $userMap[$u->id] = $u;
        }

        foreach ($rawData as $row) {
            if (!isset($userMap[$row->user_id])) continue;

            $totalUnread += (int)$row->cnt;
            $userMap[$row->user_id]->unreadCount = (int)$row->cnt;
            $userMap[$row->user_id]->lastChat = (object)['created_at' => $row->last_time];
            $result[] = $userMap[$row->user_id];
        }

        return response()->json(['count' => $totalUnread, 'users' => $result]);
    }

    public function refreshChatUsers(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // ✅ EXTREME-COMPRESSION: Single raw SQL JOIN query (all data in 1 query)
        $rawData = \DB::select("
            SELECT u.id, u.name, u.image,
                   COALESCE(SUM(CASE WHEN c.is_read = 0 THEN 1 ELSE 0 END), 0) as unreadCount,
                   MAX(c.created_at) as lastChatTime
            FROM users u
            LEFT JOIN chats c ON (c.sender_id = u.id AND c.receiver_id = ?)
            WHERE u.role IN ('junior', 'senior') AND u.is_deleted = 0 AND u.id != ?
            GROUP BY u.id, u.name, u.image
            ORDER BY MAX(c.created_at) DESC
            LIMIT 200
        ", [$userId, $userId]);

        if (empty($rawData)) {
            return response()->json([
                'users' => [],
                'activeUser' => null,
                'messages' => collect(),
                'lastMessageId' => null,
            ]);
        }

        // ✅ EXTREME-COMPRESSION: Format in single pass
        $chatUsersFormatted = [];
        foreach ($rawData as $row) {
            $row->unreadCount = (int)$row->unreadCount;
            $row->lastChat = $row->lastChatTime ? (object)['created_at' => $row->lastChatTime] : null;

            if ($row->lastChatTime) {
                $time = strtotime($row->lastChatTime);
                $row->lastChatDisplay = date('Y-m-d') === date('Y-m-d', $time)
                    ? date('h:i A', $time)
                    : date('d M Y', $time);
            } else {
                $row->lastChatDisplay = '';
            }
            $chatUsersFormatted[] = $row;
        }

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
