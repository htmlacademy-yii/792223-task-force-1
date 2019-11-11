<?php

return [
    'default'     => getenv('DB_CONNECTION'),
    'connections' => [
        'taskforce' => [
            'driver'    => 'mysql',
            'database'  => getenv('DB_DATABASE'),
            'username'  => getenv('DB_USERNAME'),
            'password'  => getenv('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];
