<?php

namespace App\Application\CQRS\Handler;

use App\Application\CQRS\Command\GenerateGamesBetweenDivisionsCommand;

interface GenerateGamesBetweenDivisionsCommandHandlerInterface
{
    public function handle(GenerateGamesBetweenDivisionsCommand $command): array;
}