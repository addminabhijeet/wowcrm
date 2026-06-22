<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'message_type', // 'text', 'image', 'video', 'audio', 'pdf', 'document', 'zip', 'file'
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'is_read',
        'read_at',
        'parent_id', // for reply functionality
        'is_deleted_by_sender',
        'is_deleted_by_receiver',
        'is_edited',
        'edited_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'edited_at' => 'datetime',
        'is_deleted_by_sender' => 'boolean',
        'is_deleted_by_receiver' => 'boolean',
        'is_edited' => 'boolean',
    ];

    protected $appends = [
        'formatted_time',
        'file_size_formatted',
        'message_preview',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parent()
    {
        return $this->belongsTo(Chat::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Chat::class, 'parent_id')
            ->orderBy('id', 'asc');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('h:i A');
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function scopeConversation($query, $userId, $otherUserId)
    {
        return $query
            ->where(function ($q) use ($userId, $otherUserId) {

                $q->where(function ($qq) use ($userId, $otherUserId) {

                    $qq->where('sender_id', $userId)
                        ->where('receiver_id', $otherUserId);
                })->orWhere(function ($qq) use ($userId, $otherUserId) {

                    $qq->where('sender_id', $otherUserId)
                        ->where('receiver_id', $userId);
                });
            })
            ->where('is_deleted_by_sender', 0)
            ->where('is_deleted_by_receiver', 0)
            ->orderBy('created_at', 'asc');
    }

    public function scopeUnread($query, $userId)
    {
        return $query->where('receiver_id', $userId)
            ->where('is_read', false);
    }

    public function getReadTimeAttribute()
    {
        if (!$this->read_at) {
            return '';
        }

        return $this->read_at->isToday()
            ? $this->read_at->format('h:i A')
            : $this->read_at->format('d M Y');
    }

    public function getSeenStatusAttribute()
    {
        return $this->is_read ? 'Seen' : 'Delivered';
    }

    public function getMessagePreviewAttribute()
    {
        return \Illuminate\Support\Str::limit(
            trim(strip_tags(html_entity_decode($this->message))),
            50
        );
    }
}
