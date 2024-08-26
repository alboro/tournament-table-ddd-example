<?php

namespace App\Infrastructure\Controller;

use App\Application\CQRS\Command\ResultsCommand;
use App\Application\CQRS\Handler\ResultsCommandHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class ResultsController extends AbstractController
{
    public function __construct(
        private readonly ResultsCommandHandlerInterface $handler,
    ) {
    }

    #[Route('/results', name: 'app_results', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $result = $this->handler->handle(
            new ResultsCommand(Uuid::fromString($request->get('championshipId')))
        );;

        return $this->render('championship/results.html.twig', $result);
    }
}
