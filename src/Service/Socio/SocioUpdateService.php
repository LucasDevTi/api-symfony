<?php

namespace App\Service\Socio;

use App\Entity\Empresa;
use App\Entity\Socio;
use App\Service\CpfValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SocioUpdateService
{
    private $entityManager;
    private $cpfValidator;

    public function __construct(EntityManagerInterface $entityManager, CpfValidator $cpfValidator)
    {
        $this->entityManager = $entityManager;
        $this->cpfValidator = $cpfValidator;
    }

    public function update(int $id, array $data): JsonResponse
    {
        $socio = $this->entityManager->getRepository(Socio::class)->find($id);

        if (!$socio) {
            return new JsonResponse(['error' => 'Socio não encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        if (isset($data['nome'])) {
            $socio->setNome($data['nome']);
        }

        if (isset($data['cpf'])) {
            if (!$this->cpfValidator->isCpfUniqueForUpdate($data['cpf'], $id)) {
                return new JsonResponse(['error' => 'Já existe um sócio com esse CPF'], JsonResponse::HTTP_BAD_REQUEST);
            }
            $socio->setCpf($data['cpf']);
        }

        if (isset($data['empresa_id'])) {
            $empresa = $this->entityManager->getRepository(Empresa::class)->find($data['empresa_id']);
            if (!$empresa) {
                return new JsonResponse(['error' => 'Nenhuma empresa encontrada'], JsonResponse::HTTP_NOT_FOUND);
            }
            $socio->setEmpresa($empresa);
        }

        $this->entityManager->flush();
        return new JsonResponse(['message' => 'Sócio atualizado com sucesso'], JsonResponse::HTTP_OK);
    }
}
