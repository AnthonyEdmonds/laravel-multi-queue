<?php

namespace AnthonyEdmonds\LaravelMultiQueue;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Queue::addConnector('multi-queue', function () {
            return new DatabaseConnector($this->app['db']);
        });
    }
}
