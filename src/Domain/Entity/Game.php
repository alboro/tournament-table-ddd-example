<?php

namespace App\Domain\Entity;

use App\Domain\Exception\DomainException;
use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;

class Game
{
    private ?int $teamOneScore = null;
    private ?int $teamTwoScore = null;
    private readonly DateTimeImmutable $createdAt;

    public function __construct(
        private readonly Uuid $id,
        private readonly Team $teamOne,
        private readonly Team $teamTwo,
        private readonly ?int $dividerOfTheFinal = null,
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function teamOne(): Team
    {
        return $this->teamOne;
    }

    public function teamOneScore(): ?int
    {
        return $this->teamOneScore;
    }

    public function teamTwo(): Team
    {
        return $this->teamTwo;
    }

    public function teamTwoScore(): ?int
    {
        return $this->teamTwoScore;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function dividerOfTheFinal(): int
    {
        return $this->dividerOfTheFinal;
    }

    public function generateResults(): void
    {
        while (true) {
            try {
                $this->gameResultedWith(rand(1, 10), rand(1, 10));
                break;
            } catch (DomainException) {
            }
        }
    }

    public function gameResultedWith(int $teamOneScore, int $teamTwoScore): void
    {
        if ($teamOneScore === $teamTwoScore) {
            throw new DomainException('Result of the game cannot be the draw. Additional time!');
        }
        $this->teamOneScore = $teamOneScore;
        $this->teamTwoScore = $teamTwoScore;
    }

    public function isInsideDivision(): bool
    {
        return $this->teamTwo->type() === $this->teamOne->type();
    }

    public function winnerTeam(): Team
    {
        if (null === $this->teamOneScore || null === $this->teamTwoScore) {
            throw new DomainException('Team one score and/or two score are not set!');
        }
        return $this->teamOneScore > $this->teamTwoScore ? $this->teamOneScore : $this->teamTwoScore;
    }
}
