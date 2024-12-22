<?php

namespace App\Service\Socio;

use App\Entity\Socio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;

class SocioDeleteService
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

        $socio = $this->entityManager->getRepository(Socio::class)->find($id);

        if (!$socio) {
            return new JsonResponse(['error' => 'Socio não encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($socio);
        $this->entityManager->flush();
        return new JsonResponse(['message' => 'Sócio excluido com sucesso'], JsonResponse::HTTP_OK);
    }
}
