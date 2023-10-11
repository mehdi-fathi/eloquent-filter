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
    ]

];
