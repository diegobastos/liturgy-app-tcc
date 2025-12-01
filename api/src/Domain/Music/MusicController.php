<?php
namespace Src\Domain\Music;

use Base\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Domain\Music\DTOs\CreateMusicDTO;
use Src\Domain\Music\DTOs\UpdateMusicDTO;
use Src\Domain\Music\UseCases\MusicCreateUseCase;
use Src\Domain\Music\UseCases\MusicUpdateUseCase;
use Src\Domain\Music\UseCases\MusicDeleteUseCase;
use Src\Domain\Music\UseCases\MusicFindByIdUseCase;
use Src\Domain\Music\UseCases\MusicFindAllUseCase;
use Src\Domain\Music\UseCases\MusicFindAllPagedUseCase;
use Src\Domain\Music\UseCases\MusicFindByCriteriaUseCase;

class MusicController extends BaseController
{
    public function createOne(Request $request, Response $response): Response
    {
        try {
            $dto = new CreateMusicDTO($request->getParsedBody());
            $item = MusicCreateUseCase::getInstance()($dto);
            return $this->json($response, ['data' => $item->toArray()], 201);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function updateOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $dto = new UpdateMusicDTO($request->getParsedBody());
            $item = MusicUpdateUseCase::getInstance()($id, $dto);
            return $this->json($response, ['data' => $item->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function deleteOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            MusicDeleteUseCase::getInstance()($id);
            return $this->json($response, ['message' => 'Item excluído com sucesso']);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $item = MusicFindByIdUseCase::getInstance()($id);
            return $this->json($response, ['data' => $item->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getAll(Request $request, Response $response): Response
    {
        try {
            $items = MusicFindAllUseCase::getInstance()();
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
            $items = MusicFindAllUseCase::getInstance()($page, $limit);

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

    // public function getByCriteria(Request $request, Response $response): Response
    // {
    //     try {
    //         $criteria = $request->getQueryParams();
    //         $items = MusicFindByCriteriaUseCase::getInstance()($criteria);
    //         return $this->json($response, ['data' => $items->toArray()]);
    //     } catch (\Throwable $e) {
    //         return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
    //     }
    // }
}