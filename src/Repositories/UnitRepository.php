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

        $units = array_filter($units, function (Unit $unit) {
            if($unit->unitType !== UnitType::Defense) {
                return false;
            }
            if ($unit->goldCost === null) {
                return false;
            }

            return $unit->isBaseUnit();
        });

        usort($units, fn(Unit $unitA, Unit $unitB) => $unitA->goldCost <=> $unitB->goldCost);

        return $units;
    }
}
