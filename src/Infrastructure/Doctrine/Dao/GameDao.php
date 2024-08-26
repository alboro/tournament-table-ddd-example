<?php

namespace App\Infrastructure\Doctrine\Dao;

use App\Domain\Dao\GameDaoInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Doctrine\DBAL\Connection;

final class GameDao implements GameDaoInterface
{
    public function __construct(
        protected readonly Connection $connection,
    ) {
    }

    public function isGameWithTeamsExist(Uuid $teamOneId, Uuid $teamTwoId): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $expr = $qb->expr()->or(
            $qb->expr()->and(
                $qb->expr()->eq('g.team_one_id', ':team1'),
                $qb->expr()->eq('g.team_two_id', ':team2'),
            ),
            $qb->expr()->and(
                $qb->expr()->eq('g.team_one_id', ':team2'),
                $qb->expr()->eq('g.team_two_id', ':team1'),
            ),
        );

        $qb
            ->select('true')
            ->from('game', 'g')
            ->where($expr)
            ->setParameter('team1', $teamOneId, UuidType::NAME)
            ->setParameter('team2', $teamTwoId, UuidType::NAME);

        return $qb
                ->executeQuery()
                ->rowCount() !== 0;
    }
}