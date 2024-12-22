<?php

namespace App\Service\Empresa;

use App\Entity\Empresa;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;

class EmpresaDeleteService
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function delete(int $id): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Usuário não autenticado'], 401);
        }

        if ($user->getTipoUsuario() !== 1) {
            return new JsonResponse(['error' => 'Apenas usuários do tipo Admin podem cadastrar empresas'], 403);
        }

        $empresa = $this->entityManager->getRepository(Empresa::class)->find($id);

        if (!$empresa) {
            return new JsonResponse(['error' => 'Empresa não encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($empresa);
        $this->entityManager->flush();
        return new JsonResponse(['message' => 'Empresa excluida com sucesso'], JsonResponse::HTTP_OK);
    }
}
