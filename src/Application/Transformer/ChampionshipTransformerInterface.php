<?php

namespace App\Application\Transformer;

use App\Domain\Entity\Championship;

interface ChampionshipTransformerInterface
{
    public function transform(Championship $championship): array;
}