<?php
namespace Src\Domain\Auth\UseCases;

use Base\BaseUseCase;
use Exception;
use Src\Domain\Auth\Common\JwtService;
use Src\Domain\Auth\DTOs\RegisterDTO;
use Src\Domain\User\DTOs\CreateUserDTO;
use Src\Domain\User\UseCases\UserCreateUseCase;
use Src\Domain\User\UserRepository;

final class AuthRegisterUseCase extends BaseUseCase
{
    public function __invoke(RegisterDTO $newUser): mixed
    {      
        try {
            $userDto = new CreateUserDTO($newUser->toArray());
            $user = UserCreateUseCase::getInstance()($userDto);
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
                'message' => 'Usuário criado. Autenticação realizada com sucesso.',
                'token'   => $token,
                'email'   => $user->email,
                'name'    => $user->name,
                //'grants'  => $user->grant_names,
            ];

        } catch (\Throwable $e) {
            return [
                'status'  => $e->getCode() || 500,
                'message' => 'Falha ao criar usuário',
                'error'   => $e->getMessage()
            ];
        }
    }    
}