<?php
namespace Src\Domain\Scale;

use Base\BaseController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Domain\Scale\DTOs\CreateScaleDTO;
use Src\Domain\Scale\DTOs\UpdateScaleDTO;
use Src\Domain\Scale\UseCases\ScaleCreateUseCase;
use Src\Domain\Scale\UseCases\ScaleUpdateUseCase;
use Src\Domain\Scale\UseCases\ScaleDeleteUseCase;
use Src\Domain\Scale\UseCases\ScaleFindByIdUseCase;
use Src\Domain\Scale\UseCases\ScaleFindAllUseCase;
use Src\Domain\Scale\UseCases\ScaleFindAllPagedUseCase;
use Src\Domain\Scale\UseCases\ScaleFindByCriteriaUseCase;

class ScaleController extends BaseController
{
    public function createOne(Request $request, Response $response): Response
    {
        try {
            $dto = new CreateScaleDTO($request->getParsedBody());
            try {
                $item = ScaleCreateUseCase::getInstance()($dto);
            } catch (Exception $e) {
                print_r($e->getMessage()); die();
            }
            return $this->json($response, ['data' => $item->toArray()], 201);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()],  500);
        }
    }

    public function updateOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $dto = new UpdateScaleDTO($request->getParsedBody());
            $item = ScaleUpdateUseCase::getInstance()($id, $dto);
            return $this->json($response, ['data' => $item->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], 500);
        }
    }

    public function deleteOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            ScaleDeleteUseCase::getInstance()($id);
            return $this->json($response, ['message' => 'Item excluído com sucesso']);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $item = ScaleFindByIdUseCase::getInstance()($id);
            return $this->json($response, ['data' => $item->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getAll(Request $request, Response $response): Response
    {
        try {
            $items = ScaleFindAllUseCase::getInstance()();
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
            $items = ScaleFindAllUseCase::getInstance()($page, $limit);

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
            $items = ScaleFindByCriteriaUseCase::getInstance()($criteria);
            return $this->json($response, ['data' => $items->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }
    */
}