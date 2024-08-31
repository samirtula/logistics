<?php

return [
    'logistic_storage' => [
            'driver' => 'daily',
            'path' => storage_path('logs/logistic_storage.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],
];
