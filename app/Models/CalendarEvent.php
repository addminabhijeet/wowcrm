<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'start_time', 'end_time', 'title', 'status', 'pause_type', 'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
