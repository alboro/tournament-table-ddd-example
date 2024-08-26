<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Team;
use App\Domain\Repository\TeamRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends ServiceEntityRepository implements TeamRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function findTeam(Uuid $id): ?Team
    {
        return $this->find($id);
    }
}
