<?php
namespace Src\Domain\Auth;

use Base\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Domain\Auth\DTOs\LoginDTO;
use Src\Domain\Auth\DTOs\RegisterDTO;
use Src\Domain\Auth\UseCases\AuthLoginUseCase;
use Src\Domain\Auth\UseCases\AuthRegisterUseCase;
use Src\Domain\User\UserRepository;

class AuthController extends BaseController
{
    public function login(Request $request, Response $response, array $args): Response
    {
        try {
            $login = new LoginDTO($request->getParsedBody());

            if ($login->isEmpty()) {
                return $this->json($response, ['error' => 'Dados inválidos.'], 400);
            }

            $ret = AuthLoginUseCase::getInstance(new UserRepository())($login);
            return $this->json($response, $ret, 200);

        } catch (\Throwable $e) {
            return $this->json($response, [
                'error' => 'Erro inesperado.',
                'message' => $e->getMessage()
            ], $e->getCode() ?? 500);
        }
    }

    public function register(Request $request, Response $response, array $args): Response
    {
        try {
            $user = new RegisterDTO($request->getParsedBody());
            $user = AuthRegisterUseCase::getInstance(new UserRepository())($user);
            return $this->json($response, $user, 200);

        } catch (\Throwable $e) {
            return $this->json($response, [
                'error' => 'Erro inesperado.',
                'message' => $e->getMessage()
            ], $e->getCode() ?? 500);            
        }
    }
}