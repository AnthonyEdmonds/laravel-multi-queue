# laravel-multi-queue

Have a Laravel job worker listen to multiple queues instead of just one!

## About

By default Laravel Queue Workers can only monitor one queue at a time.

This library provides a `Connector` and a `Queue` driver to handle multiple queues with the same worker.

The only driver currently supported is Laravel's `database` driver.

## Usage

1. Install the library using Composer:
    ```bash
   composer require anthonyedmonds/laravel-multi-queue
   ``` 
2. Adjust your `queue` configuration file:
    * Set the `driver` of your database queue to `multi-queue`
    * Add the `queues` key with a list of queues for the connection to monitor:
    ```php
    // Provide a static list...
    'queues' => ['default', 'mail', 'other'],
    
    // Or add them from the environment...
    'queues' => explode(
        ',',
        env(
            'DB_QUEUE_MULTI',
            env('DB_QUEUE', 'default'),
        ),
    ),
    ```
