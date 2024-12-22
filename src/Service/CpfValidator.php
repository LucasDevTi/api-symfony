<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Socio;

class CpfValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isCpfUnique(string $cpf): bool
    {
        $socioExistente = $this->entityManager->getRepository(Socio::class)->findOneBy(['cpf' => $cpf]);
        return $socioExistente === null;
    }


    public function isCpfUniqueForUpdate(string $cpf, int $id): bool
    {
        $empresaExistente = $this->entityManager->getRepository(Socio::class)
            ->createQueryBuilder('s')
            ->where('s.cpf = :cpf')
            ->andWhere('s.id != :id') 
            ->setParameter('cpf', $cpf)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(); 

        return $empresaExistente === null;
    }
}
