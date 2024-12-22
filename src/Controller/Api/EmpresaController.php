<?php

namespace App\Controller\Api;

use App\Service\Empresa\EmpresaDeleteService;
use App\Service\Empresa\EmpresaCreateService;
use App\Service\Empresa\EmpresaFindService;
use App\Service\Empresa\EmpresaListService;
use App\Service\Empresa\EmpresaUpdateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class EmpresaController extends AbstractController
{
    private $empresaCreateService;
    private $empresaListService;
    private $empresaFindService;
    private $empresaUpdateService;
    private $empresaDeleteService;

    public function __construct(
        EmpresaCreateService $empresaCreateService,
        EmpresaListService $empresaListService,
        EmpresaFindService $empresaFindService,
        EmpresaUpdateService $empresaUpdateService,
        EmpresaDeleteService $empresaDeleteService
    ) {
        $this->empresaCreateService = $empresaCreateService;
        $this->empresaListService = $empresaListService;
        $this->empresaFindService = $empresaFindService;
        $this->empresaUpdateService = $empresaUpdateService;
        $this->empresaDeleteService = $empresaDeleteService;
    }

    #[Route('/api/empresa/create', name: 'empresa_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->empresaCreateService->create($data);
    }

    #[Route('/api/empresas', name: 'empresa_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->empresaListService->list();
    }

    #[Route('api/empresa/{id}', name: 'find_empresa', methods: ['GET'])]
    public function findEmpresa(int $id): JsonResponse
    {
        return $this->empresaFindService->find($id);
    }

    #[Route('api/empresa/{id}', name: 'update_empresa', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->empresaUpdateService->update($id, $data);
    }

    #[Route('api/empresa/{id}', name: 'delete_empresa', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        return $this->empresaDeleteService->delete($id);
    }
}
