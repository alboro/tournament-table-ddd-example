<?php

namespace App\Application\Transformer;

use App\Domain\Entity\Game;
use App\Domain\Enum\TeamType;

final class GameTransformer implements GameTransformerInterface
{
    public function transform(Game ...$games): array
    {
        $gamesAsType = [];

        foreach ($games as $game) {
            $gamesAsType[$game->teamOne()->type()->value][] = [
                'id' => $game->id(),
                'teamOne' => $game->teamOne()->name(),
                'teamOneScore' => $game->teamOneScore(),
                'teamTwo' => $game->teamTwo()->name(),
                'teamTwoScore' => $game->teamTwoScore(),
            ];
        }

        return [
            'championshipId' => isset($game) ? $game->teamOne()->championship()->id()->toString() : null,
            'games' => $gamesAsType,
            'teamTypes' => array_map(function (TeamType $teamType) { return $teamType->value; }, TeamType::cases()),
        ];
    }
}