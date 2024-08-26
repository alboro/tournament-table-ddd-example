<?php

namespace App\Infrastructure\Controller;

use App\Application\CQRS\Command\GenerateGamesBetweenDivisionsCommand;
use App\Application\CQRS\Handler\GenerateGamesBetweenDivisionsCommandHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class GenerateGamesBetweenDivisionsController extends AbstractController
{
    public function __construct(
        private readonly GenerateGamesBetweenDivisionsCommandHandlerInterface $handler,
    ) {
    }

    #[Route('/championship/scores', name: 'app_championship_scores', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $result = $this->handler->handle(
            new GenerateGamesBetweenDivisionsCommand(Uuid::fromString($request->get('championshipId')))
        );

        return $this->render('championship/divisions_results.html.twig', $result);
    }
}
