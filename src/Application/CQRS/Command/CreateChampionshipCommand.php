<?php

namespace App\Application\CQRS\Command;

final class CreateChampionshipCommand
{
    public function __construct(
        private int $teamCount,
    ) {
    }

    public function teamCount(): int
    {
        return $this->teamCount;
    }
}