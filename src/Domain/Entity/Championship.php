<?php

namespace App\Domain\Entity;

use App\Domain\Enum\TeamType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;

class Championship
{
    private readonly DateTimeImmutable $createdAt;

    /** @var array<Team>*/
    private Collection $teams;

    public function __construct(
        private readonly Uuid $id,
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->teams = new ArrayCollection();
    }

    public function addTeam(Team $team): void
    {
        $this->teams[] = $team;
    }

    public function teams(?TeamType $teamType = null): Collection
    {
        if (null !== $teamType) {
            return $this->teams->filter(
                function (Team $team) use ($teamType) { return $team->type() === $teamType; }
            );
        }
        return $this->teams;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
