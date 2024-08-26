<?php

namespace App\Application\Transformer;

use App\Domain\Entity\Game;

interface GameTransformerInterface
{
    public function transform(Game ...$game): array;
}