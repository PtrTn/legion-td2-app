<?php

declare(strict_types=1);

namespace App\Dto;

final class Counter
{
    public function __construct(
        public readonly Unit $defensiveUnit,
        public readonly Unit $waveUnit,
        public readonly int $attackModifier,
        public readonly int $defenseModifier,
    )
    {
    }

    public function getTotalModifier(): int
    {
        return $this->attackModifier + $this->defenseModifier;
    }
}
