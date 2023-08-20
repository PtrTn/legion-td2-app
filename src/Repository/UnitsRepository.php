<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Creature;
use App\Dto\Fighter;
use App\Dto\Mercenary;
use App\Dto\Units;
use App\Factory\UnitsFactory;
use Exception;

final class UnitsRepository
{
    private ?Units $units = null;

    public function __construct(private readonly UnitsFactory $unitsFactory)
    {
    }

    /** @return Fighter[] */
    public function getFightersBaseUnitsSortedByGoldCost(): array
    {
        $fighters = $this->getUnits()->getFighters();

        $baseUnitFighters = array_filter($fighters, fn (Fighter $fighter) => $fighter->isBaseUnit());
        usort($baseUnitFighters, fn(Fighter $fighterA, Fighter $fighterB) => $fighterA->goldCost <=> $fighterB->goldCost);

        return $baseUnitFighters;
    }

    public function getCreatureById(string $unitId): Creature
    {
        $creatures = $this->getUnits()->getCreatures();

        foreach ($creatures as $creature) {
            if ($creature->unitId === $unitId) {
                return $creature;
            }
        }

        throw new Exception(sprintf('Unable to find unit by id "%s"', $unitId));
    }

    /**
     * @param string[] $fighterShortUnitIds
     * @return Fighter[]
     */
    public function getFightersById(array $fighterShortUnitIds): array
    {
        $fighters = $this->getUnits()->getFighters();

        $matched = [];
        foreach ($fighters as $fighter) {
            if (in_array($fighter->getShortIdentifier(), $fighterShortUnitIds)) {
                $matched[] = $fighter;
            }
        }

        return $matched;
    }

    /** @return Mercenary[] */
    public function getMercenariesSortedByMythiumCost(): array
    {
        $mercenaries = $this->getUnits()->getMercenaries();
        usort($mercenaries, fn(Mercenary $mercenaryA, Mercenary $mercenaryB) => $mercenaryA->mythiumCost <=> $mercenaryB->mythiumCost);

        return $mercenaries;
    }

    private function getUnits(): Units
    {
        if ($this->units === null) {
            $this->units = $this->unitsFactory->create();
        }

        return $this->units;
    }
}
