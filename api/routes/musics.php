<?php

use Middlewares\JwtAuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Src\Domain\Grant\Grants;
use Src\Domain\Music\MusicController;

$app->group('/musics', function(RouteCollectorProxy $app) {
    $app->get('', MusicController::class .':getAllPaged'); //->add(new JwtAuthMiddleware([Grants::READ_USERS->value]));
    $app->get('/{id}', MusicController::class .':getOne'); //->add(new JwtAuthMiddleware([Grants::READ_USERS->value]));
    $app->patch('/{id}', MusicController::class .':updateOne'); //->add(new JwtAuthMiddleware([Grants::UPDATE_USERS->value]));
    $app->delete('/{id}', MusicController::class . ':deleteOne');//->add(new JwtAuthMiddleware([Grants::DELETE_USERS->value]));
    $app->post('', MusicController::class .':createOne'); //->add(new JwtAuthMiddleware([Grants::CREATE_USERS->value]));
})->add(new JwtAuthMiddleware([]));