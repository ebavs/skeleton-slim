<?php
// Routes

$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

/**
 * Import
 */
$app->map(['GET'],'/import', App\Action\ImportAction::class);
