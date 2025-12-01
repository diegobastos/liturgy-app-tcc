<?php

use Middlewares\JwtAuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Src\Domain\Auth\AuthController;

$app->group('/auth/register', function(RouteCollectorProxy $app) {
    $app->post('', AuthController::class .':register');
});

$app->group('/auth/login', function(RouteCollectorProxy $app) {
    $app->get('', \Src\Action\LoginAction::class );
    $app->post('', AuthController::class. ':login');
});

$app->group('/auth/renew', function(RouteCollectorProxy $app) {
    $app->post('', AuthController::class .':renew');
})->add(new JwtAuthMiddleware([]));

$app->get('/auth/logout', AuthController::class .':logout');
//     //->addMiddleware(new AuthMiddleware());

$app->group('/auth/recovery', function(RouteCollectorProxy $app) {
    $app->post('/request', AuthController::class .':setRecovery');
    $app->post('', AuthController::class .':recovery');    
});