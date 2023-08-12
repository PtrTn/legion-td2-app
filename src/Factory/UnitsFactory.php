<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\Creature;
use App\Dto\Fighter;
use App\Dto\Mercenary;
use App\Dto\Units;
use App\Enum\ArmorType;
use App\Enum\AttackType;
use App\Enum\UnitClass;
use Exception;
use ValueError;

final class UnitsFactory
{
    public function __construct(private readonly string $projectRoot)
    {
    }

    public function create(): Units
    {
        $unitsJson = file_get_contents($this->projectRoot . DIRECTORY_SEPARATOR . 'data/units.json');
        $unitsData = json_decode($unitsJson, true);
        $units = Units::Create();
        foreach ($unitsData as $unitData) {
            $unit = $this->createUnit($unitData);
            if ($unit !== null) {
                $units->add($unit);
            }
        }

        return $units;
    }

    private function createUnit(array $unitData): Fighter|Creature|Mercenary|null
    {
        if (empty($unitData['unitId'])) {
            throw new Exception('Missing data for unknown unit');
        }

        if (str_ends_with($unitData['unitId'], '_builder_unit_id')) {
            return null;
        }

        if (isset($unitData['flags']) && str_contains($unitData['flags'], 'flags_summoned')) {
            return null;
        }

        if (empty($unitData['unitClass'])) {
            throw new Exception(sprintf('Unknown unit class for unit %s', $unitData['unitId']));
        }

        try {
            $unitClass = UnitClass::fromValue($unitData['unitClass']);
        } catch (ValueError) {
            return null;
        }

        return match ($unitClass) {
            UnitClass::Fighter => $this->createFighter($unitData['unitId'], $unitData),
            UnitClass::Creature => $this->createCreature($unitData['unitId'], $unitData),
            UnitClass::Mercenary => $this->createMercenary($unitData['unitId'], $unitData),
        };
    }

    private function createFighter(string $unitId, array $unitData): ?Fighter
    {
        if (empty($unitData['name']) ||
            empty($unitData['description']) ||
            empty($unitData['iconPath']) ||
            empty($unitData['attackType']) ||
            empty($unitData['armorType']) ||
            empty($unitData['categoryClass']) ||
            !isset($unitData['goldCost']) ||
            !isset($unitData['upgradesFrom'])
        ) {
            throw new Exception(sprintf('Missing unit data for "%s"', $unitId));
        }

        if ($unitData['goldCost'] === "") {
            return null;
        }

        if ($unitData['categoryClass'] === 'Special') {
            return null;
        }

        return new Fighter(
            $unitId,
            $unitData['name'],
            $unitData['description'],
            $unitData['iconPath'],
            AttackType::fromValue($unitData['attackType']),
            ArmorType::fromValue($unitData['armorType']),
            $unitData['legionId'],
            (int) $unitData['goldCost'],
            $unitData['upgradesFrom']
        );
    }

    private function createCreature(string $unitId, array $unitData): ?Creature
    {
        if (empty($unitData['name']) ||
            empty($unitData['description']) ||
            empty($unitData['iconPath']) ||
            empty($unitData['attackType']) ||
            empty($unitData['armorType']) ||
            empty($unitData['categoryClass'])
        ) {
            throw new Exception(sprintf('Missing unit data for "%s"', $unitId));
        }

        if ($unitData['categoryClass'] === 'Special') {
            return null;
        }

        return new Creature(
            $unitId,
            $unitData['name'],
            $unitData['description'],
            $unitData['iconPath'],
            AttackType::fromValue($unitData['attackType']),
            ArmorType::fromValue($unitData['armorType'])
        );
    }

    private function createMercenary(string $unitId, array $unitData): ?Mercenary
    {
        if (empty($unitData['name']) ||
            empty($unitData['description']) ||
            empty($unitData['iconPath']) ||
            empty($unitData['attackType']) ||
            empty($unitData['armorType']) ||
            empty($unitData['categoryClass'] ||
            !isset($unitData['mythiumCost']))
        ) {
            throw new Exception(sprintf('Missing unit data for "%s"', $unitId));
        }

        if ($unitData['categoryClass'] === 'Special') {
            return null;
        }

        if ($unitId === 'giant_snail_unit_id') {
            return null;
        }

        return new Mercenary(
            $unitId,
            $unitData['name'],
            $unitData['description'],
            $unitData['iconPath'],
            (int) $unitData['mythiumCost'],
            AttackType::fromValue($unitData['attackType']),
            ArmorType::fromValue($unitData['armorType'])
        );
    }
}
