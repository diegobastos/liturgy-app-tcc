<?php

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\App;
use Src\Domain\Users\Repository\UsersRepository;
use Src\Domain\Users\Repository\UsersRepositoryInterface;
use Middlewares\JwtAuthMiddleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

// Load enviromment config .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Load database config
require_once __DIR__ . "/database.php";

// Build DI container instance
$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/container.php')
    ->addDefinitions([
        //Monolog
        'logger' => function() {
            $logger = new Logger('app'); // Nome do logger
            $logFile = __DIR__ . '/../logs/app.log'; // Caminho do arquivo de log
            $logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
            return $logger;
        },
        LoggerInterface::class => \DI\get('logger'),


        //UserRepositoryInterface::class => \DI\autowire(UsersRepository::class),
        JwtAuthMiddleware::class => function (ContainerInterface $container) {
            return new JwtAuthMiddleware();
        },


    ])
    ->build();

// Create App instance
return $container->get(App::class);