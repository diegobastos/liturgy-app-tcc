<?php

use Middlewares\JwtAuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Src\Domain\Scale\ScaleController;
use Src\Domain\Scale\ScaleTypeController;
 
$app->group('/scale-types', function(RouteCollectorProxy $app) {
    $app->get('', ScaleTypeController::class . ':getAll');
})->add(new JwtAuthMiddleware());

// $app->group('/scales-user/{id}', function(RouteCollectorProxy $app) {
//     $app->get('', ScaleMemberController::class . ':getByUserId');
// });

$app->group('/scales', function(RouteCollectorProxy $app) {
    $app->get('', ScaleController::class . ':getAllPaged');
    $app->get('/{id}', ScaleController::class . ':getOne');
    $app->post('', ScaleController::class . ':createOne');
    $app->patch('/{id}', ScaleController::class . ':updateOne');
    $app->delete('/{id}', ScaleController::class . ':deleteOne');
})->add(new JwtAuthMiddleware());