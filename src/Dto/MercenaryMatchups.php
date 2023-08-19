<?php

declare(strict_types=1);

namespace App\Dto;

final class MercenaryMatchups
{
    /** @param Matchup[] $matchups */
    public function __construct(public readonly Mercenary $mercenary, public readonly array $matchups)
    {

    }

    public function getTotalModifier(): int
    {
        return array_sum(array_map(fn(Matchup $matchup) => $matchup->getTotalModifier(), $this->matchups));
    }

    public function formattedTotalModifier(): string
    {
        $modifier = $this->getTotalModifier();

        return $this->formatModifier($modifier);
    }

    private function formatModifier(int $modifier): string
    {
        if ($modifier >= 0) {
            return '+' . $modifier;
        }

        return (string) $modifier;
    }
}
