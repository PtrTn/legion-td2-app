<?php

declare(strict_types=1);

namespace App\Dto;

final class Matchup
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
        return $this->formatModifier($this->attackModifier);
    }

    public function formattedDefenseModifier(): string
    {
        return $this->formatModifier($this->defenseModifier);
    }

    public function formattedTotalModifier(): string
    {
        $modifier = $this->getTotalModifier();

        return $this->formatModifier($modifier);
    }

    private function formatModifier(int $modifier): string
    {
        if ($modifier >= 0) {
            return '+' . $modifier;
        }

        return (string) $modifier;
    }
}
