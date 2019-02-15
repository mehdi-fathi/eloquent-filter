<?php

return [

    'connections' => [

        'mongodb' => [
            'name'       => 'mongodb',
            'driver'     => 'mongodb',
            'host'       => 'mongodb',
            'database'   => 'unittest',
        ],

        'dsn_mongodb' => [
            'driver'    => 'mongodb',
            'dsn'       => 'mongodb://mongodb:27017',
            'database'  => 'unittest',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => 'unittest',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
    ],

];
