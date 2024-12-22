<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): RedirectResponse
    {
        // Redireciona para a documentação
        return $this->redirectToRoute('documentation');
    }

    #[Route('/documentation', name: 'documentation')]
    public function documentation(): Response
    {
        // Aqui você pode retornar a documentação, seja com um template ou diretamente
        return $this->render('documentation/index.html.twig');
    }
}
