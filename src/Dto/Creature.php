<?php

declare(strict_types=1);

namespace App\Dto;

final class Creature extends Unit
{
    public function __construct(Unit $unit)
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
}
