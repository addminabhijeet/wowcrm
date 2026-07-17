<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait OptimizedQueries
{
    /**
     * Apply common optimizations to queries
     */
    public function scopeOptimized(Builder $query)
    {
        return $query->select($this->getTable() . '.*');
    }

    /**
     * Cache query results for specified duration
     */
    public function scopeCacheable(Builder $query, $minutes = 60)
    {
        $cacheKey = 'query_' . md5($query->toSql() . serialize($query->getBindings()));

        return $query->remember($minutes)->cacheDriver('redis');
    }

    /**
     * Limit results to prevent memory issues
     */
    public function scopeWithLimit(Builder $query, $limit = 1000)
    {
        return $query->limit($limit);
    }

    /**
     * Only get active records
     */
    public function scopeActive(Builder $query)
    {
        if (in_array('status', $this->fillable)) {
            $query->where('status', 1);
        }

        if (in_array('is_deleted', $this->fillable)) {
            $query->where('is_deleted', 0);
        }

        return $query;
    }
}
