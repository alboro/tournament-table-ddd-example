<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Game;
use Symfony\Component\Uid\Uuid;

interface GameRepositoryInterface
{
    public function findGame(Uuid $id): ?Game;
    public function save(Game $game): void;
}