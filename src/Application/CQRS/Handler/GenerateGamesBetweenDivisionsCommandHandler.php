<?php

namespace App\Application\CQRS\Handler;

use App\Application\CQRS\Command\GenerateGamesBetweenDivisionsCommand;
use App\Application\Transformer\GameTransformerInterface;
use App\Domain\Dao\GameDaoInterface;
use App\Domain\Entity\Game;
use App\Domain\Entity\Team;
use App\Domain\Enum\TeamType;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\ChampionshipRepositoryInterface;
use App\Domain\Repository\GameRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class GenerateGamesBetweenDivisionsCommandHandler implements GenerateGamesBetweenDivisionsCommandHandlerInterface
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly GameRepositoryInterface $gameRepository,
        private readonly GameDaoInterface $gameDao,
        private readonly GameTransformerInterface $gameTransformer,
    ) {
    }

    /**
     * Generate games inside A and B divisions.
     */
    public function handle(GenerateGamesBetweenDivisionsCommand $command): array
    {
        $championship = $this->championshipRepository->findChampionship($command->championshipId());
        if (null === $championship) {
            throw new DomainException('Championship not found');
        }
        $createdGames = [];

        foreach (TeamType::cases() as $type) {
            $teams = $championship->teams($type);
            foreach ($teams as $teamX) {
                /** @var Team $teamX */
                foreach ($teams as $teamY) {
                    /** @var Team $teamY */
                    if ($teamY !== $teamX && !$this->gameDao->isGameWithTeamsExist($teamX->id(), $teamY->id())) {
                        $game = new Game(Uuid::v7(), $teamX, $teamY);
                        while (true) {
                            try {
                                $game->gameResultedWith(rand(1, 10), rand(1, 10));
                                break;
                            } catch (DomainException) {
                            }
                        }
                        $this->gameRepository->save($game);
                        $createdGames[] = $game;
                    }
                }
            }
        }


        return $this->gameTransformer->transform($championship, ...$createdGames);
    }
}