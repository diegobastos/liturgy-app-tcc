<?php
namespace Src\Domain\Grant;

use Base\BaseController;
use Src\Domain\Grant\GrantService;

class GrantController extends BaseController
{
    public function __construct(GrantService $service)
    {
        parent::__construct($service);
    }

    /**
     * Ex.: Método específico para buscar usuário por email.
    public function getByEmail(Request $request, Response $response, array $args): Response
    {
        try {
            $user = $this->service->findByEmail($args['email']);

            if (!$user) {
                return $this->json($response, ['error' => 'User not found'], 404);
            }

            return $this->json($response, ['data' => $user->toArray()], 200);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }    
    */

    //specific methods here
}