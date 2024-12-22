<?php

namespace App\Service\Empresa;

use App\Entity\Empresa;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class EmpresaFindService
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function find(int $id): JsonResponse
    {
        $empresa = $this->entityManager->getRepository(Empresa::class)->find($id);

        if (!$empresa) {
            return new JsonResponse(['error' => 'Empresa não encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->normalize($empresa, null, ['groups' => 'empresa:read']);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
