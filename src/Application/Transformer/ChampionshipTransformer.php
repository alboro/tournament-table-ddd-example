<?php

namespace App\Application\Transformer;

use App\Domain\Entity\Championship;
use App\Domain\Entity\Team;
use App\Domain\Enum\TeamType;

final class ChampionshipTransformer implements ChampionshipTransformerInterface
{
    public function transform(Championship $championship): array
    {
        $teamData = [];
        foreach ($championship->teams() as $team) {
            /** @var Team $team */
            $teamData[$team->type()->value][] = [
                'name' => $team->name(),
                'type' => $team->type()->value,
            ];
        }

        return [
            'championshipId' => $championship->id()->toString(),
            'teams' => $teamData,
            'teamTypes' => array_map(function (TeamType $teamType) { return $teamType->value; }, TeamType::cases()),
        ];
    }
}