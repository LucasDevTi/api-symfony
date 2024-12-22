<?php

namespace App\Service\Empresa;

use App\Entity\Empresa;
use App\Service\CnpjValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;

class EmpresaCreateService
{
    private $entityManager;
    private $cnpjValidator;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, CnpjValidator $cnpjValidator, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->cnpjValidator = $cnpjValidator;
        $this->security = $security;
    }

    public function create(array $data): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Usuário não autenticado'], 401);
        }
        
        if ($user->getTipoUsuario() !== 1) {
            return new JsonResponse(['error' => 'Apenas usuários do tipo Admin podem cadastrar empresas'], 403);
        }

        if (!isset($data['nome'], $data['cnpj'])) {
            return new JsonResponse(['error' => 'Dados incompletos'], 400);
        }

        $cnpj = preg_replace('/\D/', '', $data['cnpj']);
        if (!$this->cnpjValidator->isCnpjUnique($cnpj)) {
            return new JsonResponse(['error' => 'Já existe uma empresa com esse cnpj'], 400);
        }

        if (strlen($cnpj) != 14) {
            return new JsonResponse(['error' => 'Cnpj incorreto'], 400);
        }

        try {
            $empresa = new Empresa();
            $empresa->setNome($data['nome']);
            $empresa->setCnpj($cnpj);

            $this->entityManager->persist($empresa);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Empresa cadastrada com sucesso!'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erro ao salvar empresa: ' . $e->getMessage()], 500);
        }
    }
}
