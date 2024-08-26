<?php

namespace App\Domain\Dao;

use Symfony\Component\Uid\Uuid;

interface GameDaoInterface
{
    public function isGameWithTeamsExist(Uuid $teamOneId, Uuid $teamTwoId): bool;
}