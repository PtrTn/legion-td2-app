<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\Unit;
use App\Enum\ArmorType;
use App\Enum\AttackType;
use App\Enum\UnitType;
use Exception;

final class UnitsFactory
{
    /** @return Unit[] */
    public function create(): array
    {
        $unitsJson = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data/units.json');
        $unitsData = json_decode($unitsJson, true);
        $units = [];
        foreach ($unitsData as $unitData) {
            $units[] = $this->createUnit($unitData);
        }

        return array_filter($units);
    }

    private function createUnit(array $unitData): ?Unit
    {
        if (empty($unitData['unitId'])) {
            throw new Exception('Missing data for unknown unit');
        }

        if (str_ends_with($unitData['unitId'], '_builder_unit_id')) {
            return null;
        }

        if (empty($unitData['name']) ||
            empty($unitData['description']) ||
            empty($unitData['iconPath']) ||
            empty($unitData['attackType']) ||
            empty($unitData['armorType']) ||
            empty($unitData['categoryClass']) ||
            empty($unitData['legionId']) ||
            !isset($unitData['goldCost'])
        ) {
            throw new Exception(sprintf('Missing unit data for "%s"', $unitData['unitId']));
        }

        if ($unitData['categoryClass'] === 'Special') {
            return null;
        }

        $unitType = match ($unitData['legionId']) {
            'creature_legion_id' => UnitType::Wave,
            'nether_legion_id' => UnitType::Offense,
            default => UnitType::Defense,
        };

        return new Unit(
            $unitData['unitId'],
            $unitData['name'],
            $unitData['description'],
            $unitData['iconPath'],
            AttackType::fromValue($unitData['attackType']),
            ArmorType::fromValue($unitData['armorType']),
            $unitType,
            $unitData['legionId'],
            $unitData['goldCost'] ? (int) $unitData['goldCost'] : null,
        );
    }
}
