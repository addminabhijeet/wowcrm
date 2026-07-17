<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;

class ProcessBulkOperation implements ShouldQueue
{
    use Queueable;

    public $timeout = 3600;
    public $tries = 3;

    public function __construct(
        private string $operation,
        private array $userIds,
        private array $data
    ) {}

    public function handle()
    {
        $updated = 0;

        foreach ($this->userIds as $userId) {
            $user = User::find($userId);
            if (!$user) continue;

            match($this->operation) {
                'status' => $user->update(['status' => $this->data['status'] ?? 1]),
                'role' => $user->update(['role' => $this->data['role'] ?? 'user']),
                'delete' => $user->update(['is_deleted' => 1]),
                default => null
            };

            $updated++;
        }

        \Log::info("Bulk {$this->operation} completed: {$updated} records");
        return $updated;
    }
}
