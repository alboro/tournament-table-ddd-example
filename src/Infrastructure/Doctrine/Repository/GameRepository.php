<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Game;
use App\Domain\Repository\GameRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository implements GameRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $game): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($game);
        $entityManager->flush();
    }

    public function findGame(Uuid $id): ?Game
    {
        return $this->find($id);
    }
}
