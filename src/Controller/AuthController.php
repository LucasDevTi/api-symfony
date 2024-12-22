<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    private $jwtManager;
    private $userProvider;
    private $passwordHasher;

    public function __construct(JWTTokenManagerInterface $jWTManager, UserProviderInterface $userProvider, UserPasswordHasherInterface $passwordHasher)
    {
        $this->jwtManager = $jWTManager;
        $this->userProvider = $userProvider;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        if (!$request->isMethod('POST')) {
            return new JsonResponse([
                'error' => 'Invalid HTTP method. Use POST to access this endpoint.'
            ], 405);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            throw new HttpException(400, 'Parâmetros inválidos');
        }

        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userProvider->loadUserByIdentifier($email);


        if (!password_verify($password, $user->getPassword())) {
            throw new HttpException(401, 'Credenciais inválidas.');
        }
        
        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
