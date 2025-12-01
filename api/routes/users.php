<?php

use Middlewares\JwtAuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Src\Domain\Grant\Grants;
use Src\Domain\User\UserController;

$app->group('/users', function(RouteCollectorProxy $app) {
    $app->get('', UserController::class .':getAllPaged'); //->add(new JwtAuthMiddleware([Grants::READ_USERS->value]));
    $app->get('/{id}', UserController::class .':getOne'); //->add(new JwtAuthMiddleware([Grants::READ_USERS->value]));
    $app->patch('/{id}', UserController::class .':updateOne'); //->add(new JwtAuthMiddleware([Grants::UPDATE_USERS->value]));
    $app->delete('/{id}', UserController::class . ':deleteOne'); //->add(new JwtAuthMiddleware([Grants::DELETE_USERS->value]));
    $app->post('', UserController::class .':createOne'); //->add(new JwtAuthMiddleware([Grants::CREATE_USERS->value]));    
    $app->post('/password-change', UserController::class .':changePassword');

    $app->post('/{userId}/grants', UserController::class .':assignGrants'); //->add(new JwtAuthMiddleware([Grants::ASSIGN_GRANTS->value]));
    $app->delete('/{userId}/grants', UserController::class .':removeGrants'); //->add(new JwtAuthMiddleware([Grants::ASSIGN_GRANTS->value]));

})->add(new JwtAuthMiddleware([]));