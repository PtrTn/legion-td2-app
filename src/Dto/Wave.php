<?php

declare(strict_types=1);

namespace App\Dto;

final class Wave
{
    public function __construct(
        public readonly string $id,
        public readonly int $waveNumber,
        public readonly Fighter $unit
    )
    {
    }
}
