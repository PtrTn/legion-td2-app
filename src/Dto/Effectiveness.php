<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ArmorType;
use App\Enum\AttackType;

final class Effectiveness
{
    public function __construct(
        public readonly AttackType $attackType,
        public readonly ArmorType $armorType,
        public readonly int $modifier,
    )
    {
    }
}
