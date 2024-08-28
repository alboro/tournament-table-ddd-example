<?php

namespace App\Application\CQRS\Handler;

use App\Application\CQRS\Command\ResultsCommand;
use App\Application\Transformer\TeamTransformerInterface;
use App\Domain\Dao\TeamDaoInterface;
use App\Domain\Entity\Championship;
use App\Domain\Entity\Game;
use App\Domain\Entity\Team;
use App\Domain\Enum\TeamType;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\ChampionshipRepositoryInterface;
use App\Domain\Repository\GameRepositoryInterface;
use App\Domain\Repository\TeamRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class ResultsCommandHandler implements ResultsCommandHandlerInterface
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly TeamDaoInterface $teamDao,
        private readonly TeamRepositoryInterface $teamRepository,
        private readonly GameRepositoryInterface $gameRepository,
        private readonly TeamTransformerInterface $teamTransformer,
    ) {
    }

    public function handle(ResultsCommand $command): array
    {
        $championship = $this->championshipRepository->findChampionship($command->championshipId());
        if (null === $championship) {
            throw new DomainException('Championship not found');
        }
        $winner = $this->playFinal(
            ...$this->playSemiFinal(
                ...$this->playSemiFinal(
                    ...$this->playQuarterFinal(
                        ...$this->getTeamsTopByType($championship)
                    )
                )
            )
        );

        return $this->teamTransformer->transform($winner);
    }

    private function getTeamsTopByType(Championship $championship): array
    {
        $teamsByType = [];
        foreach (TeamType::cases() as $teamType) {
            $top = $this->teamDao->getTopByTeamType($championship->id(), $teamType);
            $teams = $this->teamRepository->findTeams(...array_map(
                static fn (array $data): Uuid => Uuid::fromBinary($data['id']),
                $top
            ));

            $teamsByType[] = array_map(
                function (array $item) use ($teams) {
                    return array_reduce($teams, function (?Team $previous, Team $team) use ($item) {
                        if (null !== $previous) {
                            return $previous;
                        }
                        return Uuid::fromBinary($item['id'])->equals($team->id()) ? $team : null;
                    });
                },
                $top
            );
        }

        return $teamsByType;
    }

    /**
     * @param array<Team> $topBestA
     * @param array<Team> $topBestB
     *
     * @return array<Team>
     */
    private function playQuarterFinal(array $topBestA, array $topBestB): array
    {
        if (count($topBestA) !== 4 && count($topBestA) !== 4) {
            throw new DomainException('Team counts in play-off from both divisions must be equal to 4.');
        }
        $playOffQuarterFinalsWinners = [];
        foreach ($topBestA as $index => $bestTeam) {
            /**
             * Teams play by principle of Christmas tree.
             * The best team plays against the weakest, where the winner goes further,
             * and the loser drops out of further participation.
             */
            /** @var Team $weakTeam */
            /** @var Team $bestTeam */
            $weakTeam = $topBestB[count($topBestB) - $index - 1];
            $game = new Game(Uuid::v7(), $bestTeam, $weakTeam, 4);
            $game->generateResults();
            $this->gameRepository->save($game);
            $playOffQuarterFinalsWinners[] = $game->winnerTeam();
        }

        return $playOffQuarterFinalsWinners;
    }

    /**
     * @return array<Team>
     */
    private function playSemiFinal(Team ...$teams): array
    {
        if (count($teams) !== 4) {
            throw new DomainException('There must be 4 teams in semi-final.');
        }

        $gameOne = new Game(Uuid::v7(), $teams[0], $teams[1], 2);
        $gameOne->generateResults();
        $this->gameRepository->save($gameOne);

        $gameTwo = new Game(Uuid::v7(), $teams[2], $teams[3], 2);
        $gameTwo->generateResults();
        $this->gameRepository->save($gameTwo);

        return [$gameOne, $gameTwo];
    }


    private function playFinal(Team ...$teams): Team
    {
        if (count($teams) !== 2) {
            throw new DomainException('There must be 2 teams in the final.');
        }

        $finalGame = new Game(Uuid::v7(), $teams[0], $teams[1], 1);
        $finalGame->generateResults();
        $this->gameRepository->save($finalGame);
        
        return $finalGame->winnerTeam();
    }
}