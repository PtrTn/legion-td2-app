<?php

declare(strict_types=1);

namespace App\Dto;

final class Counter
{
    public function __construct(
        public readonly Fighter $defensiveUnit,
        public readonly Creature $waveUnit,
        public readonly int $attackModifier,
        public readonly int $defenseModifier,
    )
    {
    }

    public function getTotalModifier(): int
    {
        return $this->attackModifier + $this->defenseModifier;
    }

    public function getFormattedAttackModifier(): string
    {
        if ($this->attackModifier > 0) {
            return '+' . $this->attackModifier;
        }

        return (string) $this->attackModifier;
    }

    public function formattedDefenseModifier(): string
    {
        if ($this->defenseModifier > 0) {
            return '+' . $this->defenseModifier;
        }

        return (string) $this->defenseModifier;
    }
}
