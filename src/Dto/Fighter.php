<?php

declare(strict_types=1);

namespace App\Dto;

final class Fighter extends Unit
{
    public function __construct(
        Unit $unit,
        public ?int $goldCost,
        public array $upgradesFrom,
    )
    {
        parent::__construct(
            $unit->unitId,
            $unit->name,
            $unit->description,
            $unit->iconPath,
            $unit->attackType,
            $unit->armorType,
        );
    }

    public function isBaseUnit(): bool
    {
       return empty($this->upgradesFrom);
    }
}
