<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;

class ProcessUserExport implements ShouldQueue
{
    use Queueable;

    public $timeout = 3600;
    public $tries = 3;

    public function __construct(private array $filters = [])
    {}

    public function handle()
    {
        $users = User::where('is_deleted', 0);

        if (!empty($this->filters)) {
            if (isset($this->filters['role'])) {
                $users->where('role', $this->filters['role']);
            }
            if (isset($this->filters['status'])) {
                $users->where('status', $this->filters['status']);
            }
        }

        $data = $users->get();
        \Log::info('User export completed: ' . count($data) . ' records');

        return $data;
    }
}
