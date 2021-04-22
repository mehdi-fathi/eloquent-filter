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

];
