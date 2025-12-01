<?php
namespace Src\Domain\User;

use Base\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Domain\User\DTOs\CreateUserDTO;
use Src\Domain\User\DTOs\UpdateUserDTO;
use Src\Domain\User\UseCases\UserCreateUseCase;
use Src\Domain\User\UseCases\UserUpdateUseCase;
use Src\Domain\User\UseCases\UserDeleteUseCase;
use Src\Domain\User\UseCases\UserFindByIdUseCase;
use Src\Domain\User\UseCases\UserFindAllUseCase;

class UserController extends BaseController
{
    public function createOne(Request $request, Response $response): Response
    {
        try {
            $dto = new CreateUserDTO($request->getParsedBody());
            $item = UserCreateUseCase::getInstance()($dto);
            return $this->json($response, ['data' => $item->toArray()], 201);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }
    
    public function updateOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $dto = new UpdateUserDTO($request->getParsedBody());
            $item = UserUpdateUseCase::getInstance()($id, $dto);
            return $this->json($response, ['data' => $item->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }
    
    public function deleteOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            UserDeleteUseCase::getInstance()($id);
            return $this->json($response, ['message' => 'Item excluído com sucesso']);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $item = UserFindByIdUseCase::getInstance()($id);
            return $this->json($response, ['data' => $item->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    
    public function getAll(Request $request, Response $response): Response
    {
        try {
            $items = UserFindAllUseCase::getInstance()();
            return $this->json($response, ['data' => $items->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getAllPaged(Request $request, Response $response): Response
    {
        try {
            $page = (int) ($request->getQueryParams()['page'] ?? 1);
            $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
            $items = UserFindAllUseCase::getInstance()($page, $limit);

            return $this->json($response, [
                'data' => $items->items(),
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'last' => $items->lastPage(),
                    'hasMorePages' => $items->hasMorePages()
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    /*
    public function getByCriteria(Request $request, Response $response): Response
    {
        try {
            $criteria = $request->getQueryParams();
            $items = UserFindByCriteriaUseCase::getInstance()($criteria);
            return $this->json($response, ['data' => $items->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }
    */
}