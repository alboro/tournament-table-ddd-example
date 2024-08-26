<?php

namespace App\Domain\Entity;

use App\Domain\Enum\TeamType;
use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;

class Team
{

    private readonly DateTimeImmutable $createdAt;

    public function __construct(
        private readonly Uuid $id,
        private readonly TeamType $type,
        private readonly Championship $championship,
        private readonly string $name,
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function type(): TeamType
    {
        return $this->type;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function championship(): Championship
    {
        return $this->championship;
    }
}
