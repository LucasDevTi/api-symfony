<?php

namespace App\Service\Empresa;

use App\Entity\Empresa;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class EmpresaListService
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
        $empresas = $this->entityManager->getRepository(Empresa::class)->findAll();
        $data = $this->serializer->serialize($empresas, 'json', ['groups' => 'empresa:read']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
