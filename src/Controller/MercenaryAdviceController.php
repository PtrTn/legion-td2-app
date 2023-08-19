<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Matchup;
use App\Dto\Fighter;
use App\Dto\MercenaryMatchups;
use App\Dto\WaveMatchups;
use App\Repository\EffectivenessRepository;
use App\Repository\UnitsRepository;
use App\Repository\WavesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MercenaryAdviceController extends AbstractController
{
    public function __construct(
        private readonly EffectivenessRepository $effectivenessRepository,
        private readonly UnitsRepository $unitsRepository,
        private readonly WavesRepository $wavesRepository,
    )
    {
    }

    #[Route('/mercenaries/{fighters}', name: 'mercenary_advice', methods: 'GET')]
    public function number(string $fighters): Response
    {
        $fighterIds = explode(',', $fighters);
        $fighters = $this->unitsRepository->getFightersById($fighterIds);

        $mercenaryMatchups = $this->calculateMercenaryAdvice($fighters);
        $waveMatchups = $this->calculateWaveAdvice($fighters);

        return $this->render(
            'mercenary_advice.twig',
            ['mercenaryMatchups' => $mercenaryMatchups, 'waveMatchups' => $waveMatchups]
        );
    }

    /**
     * @param Fighter[] $selectedFighters
     * @return MercenaryMatchups[]
     */
    private function calculateMercenaryAdvice(array $selectedFighters): array
    {
        $mercenaries = $this->unitsRepository->getMercenariesSortedByMythiumCost();
        $mercenaryMatchups = [];
        foreach ($mercenaries as $mercenary) {
            $matchups = [];
            foreach ($selectedFighters as $selectedFighter) {
                $attackModifier = $this->effectivenessRepository->getEffectiveness($selectedFighter->attackType, $mercenary->armorType);
                $defenseModifier = $this->effectivenessRepository->getEffectiveness($mercenary->attackType, $selectedFighter->armorType);
                $matchups[] = new Matchup($selectedFighter, $mercenary, $attackModifier, $defenseModifier);
            }

            usort($matchups, fn(Matchup $matchupA, Matchup $matchupB) => $matchupB->getTotalModifier() <=> $matchupA->getTotalModifier());
            $mercenaryMatchups[] = new MercenaryMatchups($mercenary, $matchups);
        }

        $mercenaryMatchups = array_filter($mercenaryMatchups, fn(MercenaryMatchups $matchup) => $matchup->getTotalModifier() > 0);
        usort($mercenaryMatchups, function (MercenaryMatchups $matchupA, MercenaryMatchups $matchupB) {
            return $matchupB->getTotalModifier() <=> $matchupA->getTotalModifier();
        });

        return $mercenaryMatchups;
    }

    /**
     * @param Fighter[] $fighters
     * @return WaveMatchups[]
     */
    private function calculateWaveAdvice(array $fighters): array
    {
        $waves = $this->wavesRepository->getAll();
        $waveMatchups = [];
        foreach ($waves as $wave) {
            $counters = [];
            foreach ($fighters as $fighter) {
                $attackModifier = $this->effectivenessRepository->getEffectiveness($wave->unit->attackType, $fighter->armorType) * -1;
                $defenseModifier = $this->effectivenessRepository->getEffectiveness($fighter->attackType, $wave->unit->armorType) * -1;
                $counters[] = new Matchup($wave->unit, $fighter, $attackModifier, $defenseModifier);
            }

            usort($counters, fn(Matchup $counterA, Matchup $counterB) => $counterB->getTotalModifier() <=> $counterA->getTotalModifier());
            $waveMatchups[] = new WaveMatchups($wave, $counters);
        }

        return $waveMatchups;
    }
}
