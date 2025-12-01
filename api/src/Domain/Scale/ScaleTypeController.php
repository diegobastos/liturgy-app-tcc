<?php
namespace Src\Domain\Scale;

use Base\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Domain\Scale\UseCases\ScaleTypeFindAllUseCase;

class ScaleTypeController extends BaseController
{
    public function getAll(Request $request, Response $response): Response
    {
        try {
            $items = ScaleTypeFindAllUseCase::getInstance(new ScaleTypeRepository())();
            return $this->json($response, ['data' => $items]);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }
}