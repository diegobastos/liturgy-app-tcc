<?php

namespace Src\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class LoginAction {
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode(['Login' => 'UI']));

        return $response->withHeader('Content-Type', 'application/json');
    }
}