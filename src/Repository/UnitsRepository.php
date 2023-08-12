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
    public function getFighters(): array
    {
        $units = $this->unitsFactory->create();

        return $units->getFighters();
    }

    public function getCreatureById(string $unitId): Creature
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
