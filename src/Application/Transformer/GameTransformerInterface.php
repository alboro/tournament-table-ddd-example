<?php

namespace App\Application\Transformer;

use App\Domain\Entity\Championship;
use App\Domain\Entity\Game;

interface GameTransformerInterface
{
    public function transform(Championship $championship, Game ...$game): array;
}