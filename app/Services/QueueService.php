<?php

namespace App\Services;

use App\Jobs\ProcessUserExport;
use App\Jobs\ProcessDataReport;
use App\Jobs\ProcessBulkOperation;
use App\Jobs\ProcessCacheFill;
use Illuminate\Support\Facades\Queue;

class QueueService
{
    public static function exportUsers(array $filters = [])
    {
        return dispatch(new ProcessUserExport($filters));
    }

    public static function generateReport(string $type, array $params = [])
    {
        return dispatch(new ProcessDataReport($type, $params));
    }

    public static function bulkUpdateUsers(string $operation, array $userIds, array $data = [])
    {
        return dispatch(new ProcessBulkOperation($operation, $userIds, $data));
    }

    public static function fillCache()
    {
        return dispatch(new ProcessCacheFill());
    }

    public static function getQueueStatus()
    {
        return [
            'failed_jobs' => \DB::table('failed_jobs')->count(),
            'pending_jobs' => \DB::table('jobs')->count(),
            'connection' => config('queue.default'),
            'driver' => config('queue.connections.' . config('queue.default') . '.driver'),
        ];
    }
}
