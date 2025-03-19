<?php

return [

    /*
     * Enable / disable EloquentFilter.
     */
    'enabled' => env('EloquentFilter_ENABLED', true),

    /*
     * Enable / disable Custom Detection EloquentFilter.
     */
    'enabled_custom_detection' => env('EloquentFilter_Custom_Detection_ENABLED', true),

    /*
    * Set key for declare request for just eloquent filter
    */
    'request_filter_key' => '', // filter

    /*
    * Set index array for ignore request by default example : [ 'show_query','new_trend' ]
    */
    'ignore_request' => [],

    'max_limit' => 20, /* It's a limitation for preventing making awful queries mistakenly by the developer or intentionally by a villain user. you can disable it just with comment it. */

    /*
    |--------------------------------------------------------------------------
    | Eloquent Filter Settings
    |--------------------------------------------------------------------------
    |
    | This is the namespace custom eloquent filter
    |
    */

    'namespace' => 'App\\ModelFilters\\',

    'log' => [
        'has_keeping_query' => false,
        'max_time_query' => null,
        'type' => 'eloquentFilter.query'
    ],
    'filtering_keys' => [
    ],

    /*
    * Set salt for encode request.
    */
    'request_salt' => 1234,

    /*
    * Cast sign method is prefix name method for change data before filtering.
    */
    'cast_method_sign' => 'filterSet',

    /*
    * custom sign method is prefix name method for custom methods in models.
    */
    'custom_method_sign' => 'filterCustom',

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure the rate limiting for filter requests. This helps prevent
    | abuse and ensures optimal performance of your application.
    |
    */
    'rate_limit' => [
        // Whether to enable rate limiting
        'enabled' => env('EloquentFilter_RATE_LIMIT_ENABLED', false),

        // Maximum number of attempts within the decay minutes
        'max_attempts' => env('EloquentFilter_RATE_LIMIT', 60),

        // Number of minutes until the rate limit resets
        'decay_minutes' => env('EloquentFilter_RATE_DECAY', 1),

        // Whether to include rate limit headers in the response
        'include_headers' => env('EloquentFilter_RATE_LIMIT_HEADERS', true),

        // Custom response message when rate limit is exceeded
        'error_message' => env('EloquentFilter_RATE_LIMIT_MESSAGE', 'Too many filter requests. Please try again later.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching settings for rate limiting.
    |
    */
    'cache' => [
        // Cache prefix for rate limiting keys
        'prefix' => 'eloquent_filter_rate_limit:',

        // Cache store to use for rate limiting
        'store' => env('EloquentFilter_CACHE_DRIVER', null),
    ],
];
