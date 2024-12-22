<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Empresa;

class CnpjValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isCnpjUnique(string $cnpj): bool
    {
        $empresaExistente = $this->entityManager->getRepository(Empresa::class)->findOneBy(['cnpj' => $cnpj]);
        return $empresaExistente === null;
    }

    public function isCnpjUniqueForUpdate(string $cnpj, int $id): bool
    {
        $empresaExistente = $this->entityManager->getRepository(Empresa::class)
            ->createQueryBuilder('e')
            ->where('e.cnpj = :cnpj')
            ->andWhere('e.id != :id') 
            ->setParameter('cnpj', $cnpj)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(); 

        return $empresaExistente === null;
    }
}
