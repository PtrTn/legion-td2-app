<?php

declare(strict_types=1);

namespace App\Dto;

final class Counter
{
    public function __construct(
        public readonly Unit $attackingUnit,
        public readonly Unit $defendingUnit,
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

    public function formattedTotalModifier(): string
    {
        $modifier = $this->getTotalModifier();
        if ($modifier > 0) {
            return '+' . $modifier;
        }

        return (string) $modifier;
    }
}
