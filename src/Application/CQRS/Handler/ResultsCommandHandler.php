<?php

namespace App\Application\CQRS\Handler;

use App\Application\CQRS\Command\ResultsCommand;
use App\Domain\Dao\TeamDaoInterface;
use App\Domain\Enum\TeamType;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\ChampionshipRepositoryInterface;

final class ResultsCommandHandler implements ResultsCommandHandlerInterface
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly TeamDaoInterface $teamDao,
    ) {
    }

    public function handle(ResultsCommand $command): array
    {
        $championship = $this->championshipRepository->findChampionship($command->championshipId());
        if (null === $championship) {
            throw new DomainException('Championship not found');
        }
        foreach (TeamType::cases() as $teamType) {
            $teamData = $this->teamDao->getTopByTeamType($championship->id(), $teamType);
            // todo
        }

        return [];
    }
}