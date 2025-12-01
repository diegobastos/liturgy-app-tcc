<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container)
                ->setBasePath($_ENV['BASE_PATH']);

        // Register routes
        (require __DIR__ . '/routes.php')($app);
        
        // Register middleware
        (require __DIR__ . '/middleware.php')($app);

        //Helpers
        (require __DIR__ . '/helpers.php');

        return $app;
    },
];