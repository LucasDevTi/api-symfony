<?php

namespace App\Service\Empresa;

use App\Entity\Empresa;
use App\Service\CnpjValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmpresaUpdateService
{
    private $entityManager;
    private $cnpjValidator;

    public function __construct(EntityManagerInterface $entityManager, CnpjValidator $cnpjValidator)
    {
        $this->entityManager = $entityManager;
        $this->cnpjValidator = $cnpjValidator;
    }

    public function update(int $id, array $data): JsonResponse
    {
        $empresa = $this->entityManager->getRepository(Empresa::class)->find($id);

        if (!$empresa) {
            return new JsonResponse(['error' => 'Empresa não encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

        if (isset($data['nome'])) {
            $empresa->setNome($data['nome']);
        }

        if (isset($data['cnpj'])) {
            if (!$this->cnpjValidator->isCnpjUniqueForUpdate($data['cnpj'], $id)) {
                return new JsonResponse(['error' => 'Já existe uma empresa com esse cnpj'], JsonResponse::HTTP_BAD_REQUEST);
            }
            $empresa->setCnpj($data['cnpj']);
        }

        $this->entityManager->flush();
        return new JsonResponse(['message' => 'Empresa atualizada com sucesso'], JsonResponse::HTTP_OK);
    }
}
