<?php
// Routes

$app->group('/' . $settings['settings']['base_path'], function() {

    $this->get('/', App\Action\HomeAction::class)->setName('homepage');

    $this->map(['GET'],'/controller', App\Controllers\HomeController::class)->setName('datatable');
    $this->map(['GET'],'/controller/data', 'App\Controllers\HomeController:getData')->setName('datatable-json');

});
