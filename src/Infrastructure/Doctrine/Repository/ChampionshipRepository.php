<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Championship;
use App\Domain\Repository\ChampionshipRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Championship>
 */
class ChampionshipRepository extends ServiceEntityRepository implements ChampionshipRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Championship::class);
    }

    public function save(Championship $championship): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($championship);
        $entityManager->flush();
    }

    public function findChampionship(Uuid $id): ?Championship
    {
        return $this->find($id);
    }
}
