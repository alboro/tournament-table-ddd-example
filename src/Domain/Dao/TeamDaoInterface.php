<?php

namespace App\Domain\Dao;

use App\Domain\Enum\TeamType;
use Symfony\Component\Uid\Uuid;

interface TeamDaoInterface
{
    public function getTopByTeamType(Uuid $championshipId, TeamType $teamType): array;
}