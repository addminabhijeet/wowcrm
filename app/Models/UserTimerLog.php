<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserTimerLog extends Model
{
    protected $fillable = [
        'user_id',
        'login_id',
        'start_time',
        'remaining_seconds',
        'status',
        'pause_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
