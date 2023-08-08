<?php

declare(strict_types=1);

namespace App\Dto;

final class WaveCounters
{
    public function __construct(public readonly Wave $wave, public readonly array $counters)
    {

    }

}
