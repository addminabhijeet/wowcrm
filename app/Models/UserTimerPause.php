<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTimerPause extends Model
{
    protected $fillable = [
        'timer_log_id','pause_type','started_at','ended_at','duration_seconds'
    ];

    public function timerLog()
    {
        return $this->belongsTo(UserTimerLog::class, 'timer_log_id');
    }
}


