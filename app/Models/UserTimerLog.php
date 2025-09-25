<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserTimerLog extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'login_id',
        'start_time',
        'remaining_seconds',
        'status',
        'pause_type',
        'button_status',
        'notice_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
