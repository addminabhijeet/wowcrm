<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyTarget extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'year', 'month', 'target'];
    protected $table = 'monthly_targets';

    /**
     * Get the user that owns this monthly target
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to find targets by user, year, and month
     */
    public function scopeForUserMonth($query, $userId, $year, $month)
    {
        return $query->where('user_id', $userId)
                     ->where('year', $year)
                     ->where('month', $month);
    }

    /**
     * Get target for a specific user and month (with default 1000)
     */
    public static function getTarget($userId, $year, $month, $default = 1000)
    {
        $target = self::where('user_id', $userId)
                      ->where('year', $year)
                      ->where('month', $month)
                      ->first();

        return $target ? $target->target : $default;
    }

    /**
     * Ensure default monthly targets exist for a user
     */
    public static function ensureDefaults($userId, $year)
    {
        for ($month = 1; $month <= 12; $month++) {
            self::firstOrCreate(
                [
                    'user_id' => $userId,
                    'year' => $year,
                    'month' => $month
                ],
                [
                    'target' => 1000 // Default target
                ]
            );
        }
    }
}
