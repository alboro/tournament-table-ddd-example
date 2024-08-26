<?php

namespace App\Infrastructure\Controller;

use App\Application\CQRS\Command\CreateChampionshipCommand;
use App\Application\CQRS\Handler\CreateChampionshipCommandHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateChampionshipController extends AbstractController
{
    public function __construct(
        private readonly CreateChampionshipCommandHandlerInterface $handler,
    ) {
    }

    #[Route('/championship/create', name: 'app_create_championship', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $result = $this->handler->handle(
            new CreateChampionshipCommand((int) $request->get('teamCount', 0))
        );

        return $this->render(
            'championship/championship_created.html.twig',
            $result
        );
    }
}
