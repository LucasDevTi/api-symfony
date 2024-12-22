<?php

namespace App\Service\Socio;

use App\Entity\Socio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class SocioFindService
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
        $socio = $this->entityManager->getRepository(Socio::class)->find($id);

        if (!$socio) {
            return new JsonResponse(['error' => 'Socio não encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->normalize($socio, null, ['groups' => 'socio:read']);

        return new JsonResponse($data, JsonResponse::HTTP_OK);

    }
}
