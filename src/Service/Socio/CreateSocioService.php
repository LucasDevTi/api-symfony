<?php

namespace App\Service\Socio;

use App\Entity\Empresa;
use App\Entity\Socio;
use App\Service\CpfValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;

class CreateSocioService
{
    private $entityManager;
    private $cpfValidator;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, CpfValidator $cpfValidator, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->cpfValidator = $cpfValidator;
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

        if (!isset($data['nome'], $data['cpf'], $data['empresa_id'])) {
            return new JsonResponse(['error' => 'Dados incompletos'], 400);
        }

        $cpf = preg_replace('/\D/', '', $data['cpf']);
        if (!$this->cpfValidator->isCpfUnique($cpf)) {
            return new JsonResponse(['error' => 'Já existe um socio com esse cpf'], 400);
        }

        if (strlen($cpf) != 11) {
            return new JsonResponse(['error' => 'Cpf incorreto'], 400);
        }

        $empresa = $this->entityManager->getRepository(Empresa::class)->find($data['empresa_id']);
        if (!$empresa) {
            return new JsonResponse(['error' => 'Nenhuma empresa encontrada'], 404);
        }

        try {
            $socio = new Socio();
            $socio->setNome($data['nome']);
            $socio->setCpf($cpf);
            $socio->setEmpresa($empresa);

            $this->entityManager->persist($socio);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Sócio cadastrado com sucesso!'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erro ao salvar sócio: ' . $e->getMessage()], 500);
        }
    }
}
