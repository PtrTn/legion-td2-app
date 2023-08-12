<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Unit;
use App\Enum\UnitType;
use App\Factory\UnitsFactory;
use Exception;

final class UnitsRepository
{
    public function __construct(private readonly UnitsFactory $unitsFactory)
    {
    }

    /** @return Unit[] */
    public function getFighters(): array
    {
        $units = $this->unitsFactory->create();

        $units = array_filter($units, function (Unit $unit) {
            if($unit->unitType !== UnitType::Fighter) {
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

    public function getById(string $unitId): Unit
    {
        $units = $this->unitsFactory->create();

        foreach ($units as $unit) {
            if ($unit->unitId === $unitId) {
                return $unit;
            }
        }

        throw new Exception(sprintf('Unable to find unit by id "%s"', $unitId));
    }
}
