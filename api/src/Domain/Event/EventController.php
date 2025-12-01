<?php
namespace Src\Domain\Event;

use Base\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Domain\Event\DTOs\CreateEventDTO;
use Src\Domain\Event\DTOs\UpdateEventDTO;
use Src\Domain\Event\UseCases\EventCreateUseCase;
use Src\Domain\Event\UseCases\EventUpdateUseCase;
use Src\Domain\Event\UseCases\EventDeleteUseCase;
use Src\Domain\Event\UseCases\EventFindByIdUseCase;
use Src\Domain\Event\UseCases\EventFindAllUseCase;
use Src\Domain\Event\UseCases\EventFindByCriteriaUseCase;

class EventController extends BaseController
{
    public function createOne(Request $request, Response $response): Response
    {
        try {
            $dto = new CreateEventDTO($request->getParsedBody());
            $item = EventCreateUseCase::getInstance()($dto);
            return $this->json($response, ['data' => $item->toArray()], 201);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function updateOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $dto = new UpdateEventDTO($request->getParsedBody());
            $item = EventUpdateUseCase::getInstance()($id, $dto);
            return $this->json($response, ['data' => $item->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function deleteOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            EventDeleteUseCase::getInstance()($id);
            return $this->json($response, ['message' => 'Item excluído com sucesso']);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getOne(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $item = EventFindByIdUseCase::getInstance()($id);
            return $this->json($response, ['data' => $item->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getAll(Request $request, Response $response): Response
    {
        try {
            $items = EventFindAllUseCase::getInstance()();
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
            $items = EventFindAllUseCase::getInstance()($page, $limit);

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
            $items = EventFindByCriteriaUseCase::getInstance()($criteria);
            return $this->json($response, ['data' => $items->toArray()]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }
    */
}