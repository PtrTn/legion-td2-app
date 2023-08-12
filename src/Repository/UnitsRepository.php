<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Creature;
use App\Dto\Fighter;
use App\Factory\UnitsFactory;
use Exception;

final class UnitsRepository
{
    public function __construct(private readonly UnitsFactory $unitsFactory)
    {
    }

    /** @return Fighter[] */
    public function getFightersBaseUnitsSortedByGoldCost(): array
    {
        $units = $this->unitsFactory->create();
        $fighters = $units->getFighters();

        $baseUnitFighters = array_filter($fighters, fn (Fighter $fighter) => $fighter->isBaseUnit());
        usort($baseUnitFighters, fn(Fighter $fighterA, Fighter $fighterB) => $fighterA->goldCost <=> $fighterB->goldCost);

        return $baseUnitFighters;
    }

    public function getCreatureById(string $unitId): Creature
    {
        $units = $this->unitsFactory->create();
        $creatures = $units->getCreatures();

        foreach ($creatures as $creature) {
            if ($creature->unitId === $unitId) {
                return $creature;
            }
        }

        throw new Exception(sprintf('Unable to find unit by id "%s"', $unitId));
    }
}
