<?php

namespace Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Nyholm\Psr7\Response as NyholmResponse;
use Src\Domain\Auth\Common\JwtService;

class JwtAuthMiddleware implements MiddlewareInterface
{
    private array $grants;

    public function __construct(array $grants = [])
    {
        $this->grants = $grants;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader[0], $matches)) {
            $response = new NyholmResponse();
            $ret = [
                'status' => 401,
                'message' => 'Sem autorização',
                'data' => $request->getUri()->getPath()
            ];
            $response->getBody()->write(json_encode($ret));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $token = $matches[1];

        try {
            $decoded = JwtService::validateToken($token);
            $userAuth = new UserDataAuth($decoded);
            $request = $request->withAttribute('user', $userAuth);

            if (!empty($this->grants) && !$this->hasRequiredGrant($decoded)) {
                $response = new NyholmResponse();
                $ret = [
                    'status' => 403,
                    'message' => 'Ação não disponível para este perfil',
                    'data' => "{$request->getMethod()} {$request->getUri()->getPath()}"
                ];
                $response->getBody()->write(json_encode($ret));
                return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
            }

        } catch (\Exception $e) {
            $response = new NyholmResponse();
            $ret = [
                'status' => 401,
                'message' => 'Sem autorização',
                'data' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($ret));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }

    private function hasRequiredGrant($decoded): bool
    {
        $userGrants = $decoded->grants ?? [];
        $userGrants = is_array($userGrants) ? $userGrants : explode(',', $userGrants);
        return !empty(array_intersect($userGrants, $this->grants));
    }
}