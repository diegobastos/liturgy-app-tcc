<?php
namespace Src\Domain\Auth\UseCases;

use Base\BaseUseCase;
use Src\Domain\Auth\Common\JwtService;
use Src\Domain\Auth\DTOs\LoginDTO;
use Src\Domain\User\UseCases\UserLoginUseCase;
use Src\Domain\User\UserRepository;

final class AuthLoginUseCase extends BaseUseCase
{
    public function __invoke(LoginDTO $login): mixed
    {
        try {           
            $user = UserLoginUseCase::getInstance(new UserRepository())($login->getUsernameEmail(), $login->password);

            if (!$user) {
                return [
                    'status' => 401,
                    'message' => 'Usuário ou senha inválidos.'
                ];
            }

            //$grants = $user->grants()->pluck('grants.id')->toArray();

            $payload = [
                'sub'      => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
                //'company'  => $user->company_id ?? 0,
                //'grants'   => $user->grant_names,
            ];

            $token = JwtService::generateToken($payload);

            return [
                'status'  => 200,
                'message' => 'Autenticação realizada com sucesso.',
                'token'   => $token,
                'email'   => $user->email,
                'name'    => $user->name,
                //'grants'  => $user->grant_names,
            ];

        } catch (\Throwable $e) {
            return [
                'status'  => 500,
                'message' => 'Erro interno na autenticação.',
                'error'   => $e->getMessage()
            ];
        }
    }    
}