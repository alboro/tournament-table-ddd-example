<?php

namespace App\Application\CQRS\Handler;

use App\Application\CQRS\Command\CreateChampionshipCommand;

interface CreateChampionshipCommandHandlerInterface
{
    public function handle(CreateChampionshipCommand $command): array;
}