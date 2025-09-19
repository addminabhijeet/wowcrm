<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function pauses()
    {
        return $this->hasMany(UserTimerPause::class, 'timer_log_id');
    }
}
