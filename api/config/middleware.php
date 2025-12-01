<?php

use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    // Add the override _METHOD feature
    $app->add(new MethodOverrideMiddleware());

    // Add auth middleware to all/app
    //$app->add(new JwtAuthMiddleware('$2aW452u4qQi12I4589QuOp`63apP32'));

    // Handle exceptions
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    $errorMiddleware->setErrorHandler(
        \Slim\Exception\HttpNotFoundException::class,
        function (\Psr\Http\Message\ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
            $response = new \Nyholm\Psr7\Response();
            $response = $response->withHeader('Content-Type', 'application/json');

            $ret = ["status" => 404, "message" => "Not Found", "data" => $request->getUri()->getPath()];
            $response->getBody()->write(json_encode($ret));
            return $response->withStatus(404);
        }
    );

    $errorMiddleware->setErrorHandler(
        \Slim\Exception\HttpMethodNotAllowedException::class,
        function (\Psr\Http\Message\ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
            $response = new \Nyholm\Psr7\Response();
            $response = $response->withHeader('Content-Type', 'application/json');

            $ret = ["status" => 405, "message" => "Not Allowed", "data" => $request->getUri()->getPath()];
            $response->getBody()->write(json_encode($ret));

            return $response->withStatus(405);
        }
    );
    
    // Middleware CORS
    $app->add(function (Request $request, RequestHandlerInterface $handler): Response {
        $allowedOrigins = explode(',', $_ENV['URL_FRONT'] ?? '');
        $allowedOrigins = array_map('trim', $allowedOrigins);

        $origin = $request->getHeaderLine('Origin');

        if (in_array($origin, $allowedOrigins)) {
            $response = $handler->handle($request);

            if ($request->getMethod() === 'OPTIONS') {
                return (new \Nyholm\Psr7\Response())
                    ->withHeader('Access-Control-Allow-Origin', $origin)
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                    ->withHeader('Access-Control-Allow-Credentials', 'true')
                    ->withStatus(204); // No Content
            }

            return $response
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->withHeader('Pragma', 'no-cache');
        }

        return $handler->handle($request);
    });

};