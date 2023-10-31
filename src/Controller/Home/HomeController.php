<?php

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/men', name: 'home-men')]
    public function homeMen(): Response
    {
        return $this->render('home/men.html.twig');
    }

    #[Route('/women', name: 'home-women')]
    public function homeWomen(): Response
    {
        return $this->render('home/women.html.twig');
    }

    #[Route('/')]
    public function home()
    {
        return $this->redirectToRoute('home-men');
    }

}
