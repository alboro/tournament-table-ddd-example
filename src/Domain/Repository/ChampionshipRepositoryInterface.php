<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Championship;
use Symfony\Component\Uid\Uuid;

interface ChampionshipRepositoryInterface
{
    public function save(Championship $team): void;

    public function findChampionship(Uuid $id): ?Championship;
}