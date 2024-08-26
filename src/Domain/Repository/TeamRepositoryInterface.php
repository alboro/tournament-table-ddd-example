<?php

namespace App\Domain\Repository;


use App\Domain\Entity\Team;
use Symfony\Component\Uid\Uuid;

interface TeamRepositoryInterface
{
    public function findTeam(Uuid $id): ?Team;
}