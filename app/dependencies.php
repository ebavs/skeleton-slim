<?php
// DIC configuration
include 'queryData.php';

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['twig_profile'] = function () {
    return new Twig_Profiler_Profile();
};

$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new Twig_Extension_Profiler($c['twig_profile']));

    $base_path = '';
    if (isset($settings['base_path']) && !empty($settings['base_path']) ) {
        $base_path = $c['request']->getUri()->getScheme() . '://' .
                     $c['request']->getUri()->getHost() . '/' .
                        $settings['base_path'];
    }

    $view->getEnvironment()->addGlobal('base_path', $base_path);

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};


// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------
$container['db'] = function ($c) {

    return new App\Helpers\DatabaseWrapper( $c['settings']['db'] );

};

/*******
 * MODELS
 */
$container['model.home'] = function( $c ) {
    return new App\Models\HomeModel( $c['db'] );
};


// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[App\Action\HomeAction::class] = function ($c) {
    return new App\Action\HomeAction( $c );
};

$container[App\Controllers\HomeController::class] = function ($c) {
    return new App\Controllers\HomeController( $c );
};