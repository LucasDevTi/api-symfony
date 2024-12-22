<?php

namespace App\Controller\Api;

use App\Service\Socio\CreateSocioService;
use App\Service\Socio\SocioDeleteService;
use App\Service\Socio\SocioFindService;
use App\Service\Socio\SocioListService;
use App\Service\Socio\SocioUpdateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SocioController extends AbstractController
{
    private $createSocioService;
    private $socioListService;
    private $socioFindService;
    private $socioUpdateService;
    private $socioDeleteService;

    public function __construct(
        CreateSocioService $createSocioService,
        SocioListService $socioListService,
        SocioFindService $socioFindService,
        SocioUpdateService $socioUpdateService,
        SocioDeleteService $socioDeleteService
    ) {
        $this->createSocioService = $createSocioService;
        $this->socioListService = $socioListService;
        $this->socioFindService = $socioFindService;
        $this->socioUpdateService = $socioUpdateService;
        $this->socioDeleteService = $socioDeleteService;
    }

    #[Route('/api/socio/create', name: 'socio_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return $this->createSocioService->create($data);
    }

    #[Route('/api/socios', name: 'socio_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->socioListService->list();
    }

    #[Route('api/socio/{id}', name: 'find_socio', methods: ['GET'])]
    public function findSocio(int $id): JsonResponse
    {
        return $this->socioFindService->find($id);
    }

    #[Route('api/socio/{id}', name: 'update_socio', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->socioUpdateService->update($id, $data);
    }

    #[Route('api/socio/{id}', name: 'delete_socio', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->socioDeleteService->delete($id);
    }
}
