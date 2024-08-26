<?php

namespace App\Application\CQRS\Command;

use Symfony\Component\Uid\Uuid;

final class ResultsCommand
{
    public function __construct(
        private readonly Uuid $championshipId,
    ) {
    }

    public function championshipId(): Uuid
    {
        return $this->championshipId;
    }
}