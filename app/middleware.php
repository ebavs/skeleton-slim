<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(
    new \Slim\Middleware\HttpBasicAuthentication([
        'path' => '/',
        'realm' => 'Protected',
        'secure' => false,
        'environment' => 'REDIRECT_HTTP_AUTHORIZATION',
        'authenticator' => new \Slim\Middleware\HttpBasicAuthentication\PdoAuthenticator([
            'pdo' => $app->getContainer()['db']->_conn,
            'table' => 'Users'
        ]),
        'callback' => function ($request, $response, $arguments) use ($app) {

            $container              = $app->getContainer();
            $container['user']      = $container['db']->fetchRow('SELECT `user`, display_name, role FROM Users WHERE `user` = ?', [$arguments['user']]);
            $container['view']['user'] = $container['user'];
        }
    ])
);

$app->add(new \App\Middleware\TracyDBMiddleware( $app->getContainer()['db'], $app->getContainer()['twig_profile'] ));
