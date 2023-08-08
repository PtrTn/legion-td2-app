<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Unit;
use App\Enum\UnitType;
use App\Factory\UnitsFactory;

final class UnitRepository
{
    public function __construct(private readonly UnitsFactory $unitsFactory)
    {
    }

    /** @return Unit[] */
    public function getDefensiveUnits(): array
    {
        $units = $this->unitsFactory->create();

        $units = array_filter($units, fn(Unit $unit) => $unit->unitType === UnitType::Defense && $unit->goldCost !== null);

        usort($units, fn(Unit $unitA, Unit $unitB) => [$unitA->legionId, $unitA->goldCost] <=> [$unitB->legionId, $unitB->goldCost]);

        return $units;
    }
}
