<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/create', name: 'create_user', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        if (!isset($data['username'], $data['email'], $data['password'], $data['tipo_usuario'])) {
            return new JsonResponse([
                'error' => 'Dados incompletos'
            ], 400);
        }

        $user = new Users();
        $user->setNome($data['username']);
        $user->setEmail($data['email']);
        $user->setTipoUsuario($data['tipo_usuario']);

        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Usuário criado com sucesso'
        ], 201);
    }
}
