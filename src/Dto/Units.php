<?php

declare(strict_types=1);

namespace App\Dto;

final class Units
{
    private array $units = [];

    private function __construct()
    {

    }

    public static function Create(): self
    {
        return new self();
    }

    public function add(Fighter|Creature|Mercenary $unit): void
    {
        $this->units[] = $unit;
    }

    /** @return Fighter[] */
    public function getFighters(): array
    {
        $fighters = [];
        foreach ($this->units as $unit) {
            if (!$unit instanceof Fighter) {
                continue;
            }
            if (!$unit->isBaseUnit()) {
                continue;
            }
            $fighters[] = $unit;
        }

        usort($fighters, fn(Fighter $fighterA, Fighter $fighterB) => $fighterA->goldCost <=> $fighterB->goldCost);

        return $fighters;
    }
}
