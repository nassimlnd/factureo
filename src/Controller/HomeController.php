<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('home/login.html.twig', []);
    }

    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('home/register.html.twig', []);
    }
}
