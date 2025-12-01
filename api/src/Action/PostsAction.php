<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PostsAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode(['Posts' => 'done']));

        return $response->withHeader('Content-Type', 'application/json');
    }
}