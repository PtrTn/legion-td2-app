<?php

declare(strict_types=1);

namespace App\Dto;

final class Units
{
    /** @var array<Fighter|Creature|Mercenary> */
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
        return array_filter($this->units, fn(Fighter|Creature|Mercenary $unit) => $unit instanceof Fighter);
    }

    /** @return Creature[] */
    public function getCreatures(): array
    {
        return array_filter($this->units, fn(Fighter|Creature|Mercenary $unit) => $unit instanceof Creature);
    }

    /** @return Mercenary[] */
    public function getMercenaries(): array
    {
        return array_filter($this->units, fn(Fighter|Creature|Mercenary $unit) => $unit instanceof Mercenary);
    }
}
