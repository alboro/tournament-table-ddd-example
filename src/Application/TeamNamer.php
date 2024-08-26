<?php

namespace App\Application;

use App\Domain\Enum\TeamType;

final class TeamNamer
{
    public static function name(TeamType $teamType, int $teamNumber): string
    {
        return sprintf("Team-%s%02d", $teamType->value, $teamNumber);
    }
}