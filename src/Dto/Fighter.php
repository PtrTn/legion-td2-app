<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ArmorType;
use App\Enum\AttackType;

final class Fighter
{
    public function __construct(
        public string $unitId,
        public string $name,
        public string $description,
        public string $iconPath,
        public readonly AttackType $attackType,
        public readonly ArmorType $armorType,
        public string $legionId,
        public ?int $goldCost,
        public array $upgradesFrom,
    )
    {

    }

    public function isBaseUnit(): bool
    {
       return empty($this->upgradesFrom);
    }
}
