<?php

namespace AnthonyEdmonds\LaravelMultiQueue;

use Illuminate\Database\Connection;
use Illuminate\Queue\DatabaseQueue as BaseDatabaseQueue;
use Illuminate\Queue\Jobs\DatabaseJobRecord;

class DatabaseQueue extends BaseDatabaseQueue
{
    public function __construct(
        Connection $database,
        string $table,
        string $default = 'default',
        int $retryAfter = 60,
        bool $dispatchAfterCommit = false,
        public array $queues = [],
    ) {
        parent::__construct($database, $table, $default, $retryAfter, $dispatchAfterCommit);
    }

    public function size($queue = null): int
    {
        return $this->database->table($this->table)
            ->whereIn('queue', $this->queues)
            ->count();
    }

    protected function getNextAvailableJob($queue = null): ?DatabaseJobRecord
    {
        $job = $this->database->table($this->table)
            ->lock($this->getLockForPopping())
            ->whereIn('queue', $this->queues)
            ->where(function ($query) {
                $this->isAvailable($query);
                $this->isReservedButExpired($query);
            })
            ->orderBy('id')
            ->first();

        return $job !== null
            ? new DatabaseJobRecord((object) $job)
            : null;
    }

    public function clear($queue = null): int
    {
        return $this->database->table($this->table)
            ->whereIn('queue', $this->queues)
            ->delete();
    }
}
