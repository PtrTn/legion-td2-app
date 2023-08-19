<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Matchup;
use App\Dto\Fighter;
use App\Dto\WaveMatchups;
use App\Repository\EffectivenessRepository;
use App\Repository\UnitsRepository;
use App\Repository\WavesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FighterAdviceController extends AbstractController
{
    public function __construct(
        private readonly UnitsRepository $unitsRepository,
        private readonly WavesRepository $wavesRepository,
        private readonly EffectivenessRepository $effectivenessRepository,
    )
    {
    }

    #[Route('/fighters/{fighters}', name: 'fighter_advice', methods: 'GET')]
    public function number(string $fighters): Response
    {
        $fighterIds = explode(',', $fighters);
        $fighters = $this->unitsRepository->getFightersById($fighterIds);

        return $this->showFighterAdvice($fighters);
    }

    /** @param Fighter[] $selectedFighters */
    private function showFighterAdvice(array $selectedFighters): Response
    {
        $waves = $this->wavesRepository->getAll();
        $waveMatchups = [];
        foreach ($waves as $wave) {
            $counters = [];
            foreach ($selectedFighters as $selectedFighter) {
                $attackModifier = $this->effectivenessRepository->getEffectiveness($selectedFighter->attackType, $wave->unit->armorType);
                $defenseModifier = $this->effectivenessRepository->getEffectiveness($wave->unit->attackType, $selectedFighter->armorType);
                if ($attackModifier + $defenseModifier > 0) {
                    $counters[] = new Matchup($selectedFighter, $wave->unit, $attackModifier, $defenseModifier);
                }
            }

            usort($counters, fn(Matchup $counterA, Matchup $counterB) => $counterB->getTotalModifier() <=> $counterA->getTotalModifier());
            $waveMatchups[] = new WaveMatchups($wave, $counters);
        }

        return $this->render('fighter_advice.twig', ['waveMatchups' => $waveMatchups]);
    }
}
