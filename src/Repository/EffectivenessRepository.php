<?php

declare(strict_types=1);

namespace App\Repository;

use App\Enum\ArmorType;
use App\Enum\AttackType;
use App\Factory\EffectivenessFactory;

final class EffectivenessRepository
{
    public function __construct(private readonly EffectivenessFactory $effectivenessFactory)
    {
    }

    public function getEffectiveness(AttackType $attackType, ArmorType $armorType): int
    {
        $effectivenesses = $this->effectivenessFactory->create();
        foreach ($effectivenesses as $effectiveness) {
            if ($effectiveness->attackType === $attackType && $effectiveness->armorType === $armorType) {
                return $effectiveness->modifier;
            }
        }

        return 0;
    }
}
