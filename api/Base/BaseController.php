<?php

namespace Base;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class BaseController
{
    protected function json(Response $response, array $data, int $status = 200): Response
    {
        $status = ($status >= 100 && $status <= 599) ? $status : 500;
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}