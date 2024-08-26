<?php

namespace App\Application\CQRS\Handler;

use App\Application\CQRS\Command\CreateChampionshipCommand;
use App\Application\TeamNamer;
use App\Application\Transformer\ChampionshipTransformerInterface;
use App\Domain\Entity\Championship;
use App\Domain\Entity\Team;
use App\Domain\Enum\TeamType;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\ChampionshipRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class CreateChampionshipCommandHandler implements CreateChampionshipCommandHandlerInterface
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly ChampionshipTransformerInterface $championshipTransformer,
    ) {
    }

    public function handle(CreateChampionshipCommand $command): array
    {
        if ($command->teamCount() < 4) {
            throw new DomainException('Team Count should be at least 4');
        }

        if ($command->teamCount() > 20) {
            throw new DomainException('Team Count should be less than 20');
        }

        $championship = new Championship(Uuid::v7());
        foreach (TeamType::cases() as $type) {
            for ($index = 1; $index <= $command->teamCount(); $index++) {
                $championship->addTeam(
                    new Team(Uuid::v7(), $type, $championship, TeamNamer::name($type, $index))
                );
            }
        }
        $this->championshipRepository->save($championship);

        return $this->championshipTransformer->transform($championship);
    }
}