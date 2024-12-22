<?php

namespace App\Service\Socio;

use App\Entity\Socio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class SocioListService
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function list(): JsonResponse
    {
        $socios = $this->entityManager->getRepository(Socio::class)->findAll();
        $data = $this->serializer->serialize($socios, 'json', ['groups' => 'socio:read']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
