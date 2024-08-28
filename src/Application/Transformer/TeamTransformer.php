<?php

namespace App\Application\Transformer;

use App\Domain\Entity\Team;

final class TeamTransformer implements TeamTransformerInterface
{
    public function transform(Team $team): array
    {
        return [
            'championshipId' => $team->championship()->id()->toString(),
            'team' => [
                'name' => $team->name(),
                'type' => $team->type()->value,
            ],
        ];
    }
}