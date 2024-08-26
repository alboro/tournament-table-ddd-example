<?php

namespace App\Tests\Application\CQRS\Handler;

use App\Application\CQRS\Command\CreateChampionshipCommand;
use App\Application\CQRS\Handler\CreateChampionshipCommandHandler;
use App\Application\Transformer\ChampionshipTransformerInterface;
use App\Domain\Entity\Championship;
use App\Domain\Enum\TeamType;
use App\Domain\Repository\ChampionshipRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateChampionshipCommandHandlerTest extends TestCase
{
    private CreateChampionshipCommandHandler $handler;
    private ChampionshipRepositoryInterface $championshipRepository;
    private ChampionshipTransformerInterface $championshipTransformer;

    public function test_generate_appropriate_team_count(): void
    {
        $this->handler = new CreateChampionshipCommandHandler(
            $this->championshipRepository = $this->createMock(ChampionshipRepositoryInterface::class),
            $this->championshipTransformer = $this->createMock(ChampionshipTransformerInterface::class),
        );
        $teamCount = rand(4, 20);
        $test = $this;
        $championship = null;
        $this->championshipRepository->expects(self::any())
            ->method('save')
            ->with($this->callback(function ($model) use ($test, $teamCount, &$championship) {
                $test->assertInstanceOf(Championship::class, $model);
                /* @var Championship $model */
                $this->assertCount($teamCount * count(TeamType::cases()), $model->teams());
                $championship = $model;

                return true;
            }));
        $this->championshipTransformer->expects(self::any())
            ->method('transform')
            ->with($this->callback(function ($model) use ($test, &$championship) {
                $test->assertSame($championship, $model);

                return true;
            }))
            ->willReturn([]);
        $this->handler->handle(new CreateChampionshipCommand($teamCount));
        $this->assertTrue(true);
    }
}
