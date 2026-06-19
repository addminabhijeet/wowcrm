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

    // Get conversation with another user
    public function getConversation($userId)
    {
        $authUser = Auth::user();

        if (!User::find($userId)) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $messages = Chat::conversation($authUser->id, $userId)
            ->with(['sender', 'receiver'])
            ->paginate(50);

        // Mark messages as read
        Chat::where('receiver_id', $authUser->id)
            ->where('sender_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'messages' => $messages->items(),
            'pagination' => [
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
            ],
        ]);
    }

    // Get all users with last message
    public function getUsers()
    {
        $authUser = Auth::user();

        $users = User::where('id', '!=', $authUser->id)
            ->get()
            ->map(function ($user) use ($authUser) {
                $lastMessage = Chat::conversation($authUser->id, $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $unreadCount = Chat::where('receiver_id', $authUser->id)
                    ->where('sender_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'image' => $user->image,
                    'status' => $user->status ? 'Available' : 'Away',
                    'last_message' => $lastMessage?->message_type === 'text'
                        ? Str::limit($lastMessage->message, 30)
                        : '📎 ' . $lastMessage?->file_name,
                    'last_message_time' => $lastMessage?->created_at?->format('h:i A'),
                    'unread_count' => $unreadCount,
                ];
            })
            ->sortByDesc(function ($user) {
                return $user['last_message_time'];
            })
            ->values();

        return response()->json($users);
    }

    // Send message
    public function sendMessage(Request $request)
    {
        $authUser = Auth::user();
        $receiver = User::find($request->receiver_id);

        if (!$receiver) {
            return response()->json(['error' => 'Receiver not found'], 404);
        }

        try {
            $message = new Chat();
            $message->sender_id = $authUser->id;
            $message->receiver_id = $receiver->id;

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Validate file
                $this->validateFile($file);

                // Determine message type
                $mimeType = $file->getMimeType();
                $messageType = $this->getMessageType($mimeType, $file->getClientOriginalExtension());

                // Store file
                $path = $file->store('chat-files/' . date('Y/m/d'), 'public');

                $message->message = $file->getClientOriginalName();
                $message->message_type = $messageType;
                $message->file_name = $file->getClientOriginalName();
                $message->file_path = $path;
                $message->file_size = $file->getSize();
                $message->mime_type = $mimeType;
            } else {
                // Text message with rich HTML
                $message->message = $request->message;
                $message->message_type = 'text';
            }

            $message->is_read = false;
            $message->save();

            return response()->json([
                'success' => true,
                'message' => $this->formatMessage($message),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    // Edit message
    public function editMessage(Request $request, $messageId)
    {
        $authUser = Auth::user();
        $message = Chat::find($messageId);

        if (!$message || $message->sender_id !== $authUser->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($message->message_type !== 'text') {
            return response()->json(['error' => 'Cannot edit file messages'], 400);
        }

        $message->update([
            'message' => $request->message,
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $this->formatMessage($message),
        ]);
    }

    // Delete message
    public function deleteMessage(Request $request, $messageId)
    {
        $authUser = Auth::user();
        $message = Chat::find($messageId);

        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }

        if ($message->sender_id === $authUser->id) {
            $message->is_deleted_by_sender = true;
        } elseif ($message->receiver_id === $authUser->id) {
            $message->is_deleted_by_receiver = true;
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Soft delete if deleted by both
        if ($message->is_deleted_by_sender && $message->is_deleted_by_receiver) {
            $message->delete();
        } else {
            $message->save();
        }

        return response()->json(['success' => true]);
    }

    // Download file
    public function downloadFile($messageId)
    {
        $authUser = Auth::user();
        $message = Chat::find($messageId);

        if (!$message || ($message->sender_id !== $authUser->id && $message->receiver_id !== $authUser->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$message->file_path) {
            return response()->json(['error' => 'No file attached'], 404);
        }

        $path = storage_path('app/public/' . $message->file_path);

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($path, $message->file_name);
    }


    // Mark messages as read
    public function markAsRead(Request $request)
    {
        $authUser = Auth::user();

        Chat::where('receiver_id', $authUser->id)
            ->where('sender_id', $request->sender_id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    // Search messages
    public function searchMessages(Request $request)
    {
        $authUser = Auth::user();

        $messages = Chat::where(function ($q) use ($authUser) {
            $q->where('sender_id', $authUser->id)
                ->orWhere('receiver_id', $authUser->id);
        })
            ->where('message', 'like', '%' . $request->search . '%')
            ->where('message_type', 'text')
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($messages);
    }

    // Helper method to validate file
    private function validateFile($file)
    {
        $maxSize = 50 * 1024 * 1024; // 50MB

        if ($file->getSize() > $maxSize) {
            throw new \Exception('File size exceeds 50MB limit');
        }

        $allowedMimes = [
            // Images
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            // Videos
            'video/mp4',
            'video/mpeg',
            'video/quicktime',
            'video/x-msvideo',
            // Audio
            'audio/mpeg',
            'audio/wav',
            'audio/ogg',
            'audio/mp4',
            // Documents
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            // Archives
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
            // Any file type (fallback)
            'application/octet-stream',
        ];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('File type not allowed: ' . $file->getMimeType());
        }
    }

    // Helper method to determine message type
    private function getMessageType($mimeType, $extension)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif ($mimeType === 'application/pdf') {
            return 'pdf';
        } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
            return 'zip';
        } elseif (in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            return 'document';
        }

        return 'file';
    }

    // Format message for response
    private function formatMessage($message)
    {
        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'message' => $message->message,
            'message_type' => $message->message_type,
            'file_name' => $message->file_name,
            'file_path' => $message->file_path,
            'file_size_formatted' => $message->file_size_formatted,
            'is_read' => $message->is_read,
            'read_at' => $message->read_at?->format('Y-m-d H:i:s'),
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            'formatted_time' => $message->formatted_time,
            'sender' => [
                'id' => $message->sender->id,
                'name' => $message->sender->name,
                'image' => $message->sender->image,
            ],
        ];
    }
}
