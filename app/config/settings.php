<?php
return [
    'settings' => [
        'mode'              => 'debug',

        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/../src/Views',
            'twig' => [
                'cache' => __DIR__ . '/../../cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../../log/' . date('Y-m-d') . '-app.log',
        ],

        // database
        'db'        => [
            'host'      => "127.0.0.1",
            'user'      => "root",
            'pass'      => "root",
            'dbname'    => 'sbr',
            'collation' => 'utf8mb4_unicode_ci',
            'charset'   => 'utf8mb4'
        ]
    ],
];