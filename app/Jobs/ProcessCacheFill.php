<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ProcessCacheFill implements ShouldQueue
{
    use Queueable;

    public $timeout = 1800;

    public function handle()
    {
        Cache::remember('active_users_list', 300, function () {
            return User::where('is_deleted', 0)->where('status', 1)->get();
        });

        Cache::remember('all_users_count', 3600, function () {
            return User::where('is_deleted', 0)->count();
        });

        Cache::remember('active_users_count', 3600, function () {
            return User::where('is_deleted', 0)->where('status', 1)->count();
        });

        \Log::info('Cache fill job completed');
    }
}
