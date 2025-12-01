<?php

use Middlewares\JwtAuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Src\Domain\Event\EventController;

$app->group('/events', function(RouteCollectorProxy $app) {
    $app->get('', EventController::class .':getAllPaged'); //->add(new JwtAuthMiddleware([Grants::READ_USERS->value]));
    $app->get('/{id}', EventController::class .':getOne'); //->add(new JwtAuthMiddleware([Grants::READ_USERS->value]));
    $app->patch('/{id}', EventController::class .':updateOne'); //->add(new JwtAuthMiddleware([Grants::UPDATE_USERS->value]));
    $app->delete('/{id}', EventController::class . ':deleteOne'); //->add(new JwtAuthMiddleware([Grants::DELETE_USERS->value]));
    $app->post('', EventController::class .':createOne'); //->add(new JwtAuthMiddleware([Grants::CREATE_USERS->value]));
})->add(new JwtAuthMiddleware([]));