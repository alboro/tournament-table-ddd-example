<?php

namespace App\Application\Transformer;

use App\Domain\Entity\Team;

interface TeamTransformerInterface
{
    public function transform(Team $team): array;
}