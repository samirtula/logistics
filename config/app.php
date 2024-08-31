<?php

return [
    'locale' => env('APP_LOCAL', 'en'),
    'google' => [
          'api_key' => env('GOOGLE_API_KEY'),
          'maps_uri' => env('GOOGLE_MAPS_API_URL')
        ],
    'api_key' => env('API_KEY'),
];
