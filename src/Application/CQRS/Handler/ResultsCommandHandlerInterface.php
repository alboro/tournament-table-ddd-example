<?php

namespace App\Application\CQRS\Handler;

use App\Application\CQRS\Command\ResultsCommand;

interface ResultsCommandHandlerInterface
{
    public function handle(ResultsCommand $command): array;
}