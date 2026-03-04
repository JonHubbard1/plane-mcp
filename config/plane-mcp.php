<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plane API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to Plane instances
    |
    */

    'base_url' => env('PLANE_BASE_URL', 'https://api.plane.so'),

    'api_token' => env('PLANE_API_TOKEN'),

    'workspace' => env('PLANE_WORKSPACE', 'default'),

    'timeout' => env('PLANE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Project
    |--------------------------------------------------------------------------
    |
    | Default project slug to use when none specified
    |
    */

    'default_project' => env('PLANE_DEFAULT_PROJECT'),

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for caching API responses
    |
    */

    'cache_enabled' => env('PLANE_CACHE_ENABLED', true),

    'cache_ttl' => env('PLANE_CACHE_TTL', 300), // 5 minutes
];