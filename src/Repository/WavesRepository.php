<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Wave;
use App\Factory\WavesFactory;

final class WavesRepository
{
    private ?array $waves = null;

    public function __construct(private readonly WavesFactory $wavesFactory)
    {
    }

    /** @return Wave[] */
    public function getAll(): array
    {
        return $this->getWaves();
    }

    private function getWaves(): array
    {
        if ($this->waves === null) {
            $this->waves = $this->wavesFactory->create();
        }

        return $this->waves;
    }
}
