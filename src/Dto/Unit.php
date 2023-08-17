<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ArmorType;
use App\Enum\AttackType;

class Unit
{
    public function __construct(
        public string $unitId,
        public string $name,
        public string $description,
        public string $iconPath,
        public readonly AttackType $attackType,
        public readonly ArmorType $armorType
    )
    {
    }

    public function getShortIdentifier(): string
    {
        return str_replace('_unit_id', '', $this->unitId);
    }
}
