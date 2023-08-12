<?php

declare(strict_types=1);

namespace App\Dto;

final class FighterCounters
{
    /** @param Counter[] $counters */
    public function __construct(public readonly Fighter $fighter, public readonly array $counters)
    {

    }

}
