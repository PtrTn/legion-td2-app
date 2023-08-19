<?php

declare(strict_types=1);

namespace App\Dto;

final class WaveMatchups
{
    /** @param Matchup[] $matchups */
    public function __construct(public readonly Wave $wave, public readonly array $matchups)
    {

    }

}
