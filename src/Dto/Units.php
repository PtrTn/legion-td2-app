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
            $fighters[] = $unit;
        }

        return $fighters;
    }

    /** @return Creature[] */
    public function getCreatures(): array
    {
        $creatures = [];
        foreach ($this->units as $unit) {
            if (!$unit instanceof Creature) {
                continue;
            }
            $creatures[] = $unit;
        }

        return $creatures;
    }
}
