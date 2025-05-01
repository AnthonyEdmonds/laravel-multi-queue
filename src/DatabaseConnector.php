<?php

namespace AnthonyEdmonds\LaravelMultiQueue;

use Illuminate\Queue\Connectors\DatabaseConnector as BaseDatabaseConnector;

class DatabaseConnector extends BaseDatabaseConnector
{
    public function connect(array $config): DatabaseQueue
    {
        return new DatabaseQueue(
            $this->connections->connection($config['connection'] ?? null),
            $config['table'],
            $config['queue'],
            $config['retry_after'] ?? 60,
            $config['after_commit'] ?? null,
            $config['queues'] ?? [],
        );
    }
}
