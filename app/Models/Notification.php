<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'candidate_id',
        'notifiable_role',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'string',
        'read_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    public function candidate()
    {
        return $this->belongsTo(GoogleSheetData::class, 'candidate_id');
    }
}
