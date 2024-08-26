<?php

namespace App\Infrastructure\Doctrine\Dao;

use App\Domain\Dao\TeamDaoInterface;
use App\Domain\Enum\TeamType;
use Doctrine\DBAL\Connection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

final class TeamDao implements TeamDaoInterface
{
    private const TOP_TEAM_COUNT = 4;
    public function __construct(
        protected readonly Connection $connection,
    ) {
    }

    public function getTopByTeamType(Uuid $championshipId, TeamType $teamType): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('t.id, sum(if(g.team_one_id = t.id, g.team_one_score > g.team_two_score, g.team_one_score < g.team_two_score)) AS wins, sum(if(g.team_one_id = t.id, g.team_one_score, g.team_two_score)) AS full_score')
            ->from('team', 't')
            ->join('t', 'game', 'g', 't.id = g.team_one_id OR t.id = g.team_two_id')
            ->andWhere('t.championship_id = :championshipId')
            ->andWhere('t.type = :type')
            ->setParameter('championshipId', $championshipId, UuidType::NAME)
            ->setParameter('type', $teamType->value)
            ->groupBy('t.id')
            ->addOrderBy('wins', 'DESC')
            ->addOrderBy('full_score', 'DESC')
            ->setMaxResults(self::TOP_TEAM_COUNT)
            ->executeQuery()
            ->fetchAllAssociative();
    }
}