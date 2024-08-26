<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainFormController extends AbstractController
{
    #[Route('/', name: 'default', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}
