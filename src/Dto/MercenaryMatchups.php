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

    /** @return Matchup[] */
    public function getFavorableMatchups(): array
    {
        return array_filter($this->matchups, fn(Matchup $matchup) => $matchup->getTotalModifier() >= 0);
    }

    /** @return Matchup[] */
    public function getUnfavorableMatchups(): array
    {
        return array_filter($this->matchups, fn(Matchup $matchup) => $matchup->getTotalModifier() < 0);
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
