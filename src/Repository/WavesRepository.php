<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Wave;
use App\Factory\WavesFactory;

final class WavesRepository
{
    public function __construct(private readonly WavesFactory $wavesFactory)
    {
    }

    /** @return Wave[] */
    public function getAll(): array
    {
        return $this->wavesFactory->create();
    }
}
