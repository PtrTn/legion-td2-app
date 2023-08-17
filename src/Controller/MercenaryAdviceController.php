<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Counter;
use App\Dto\Fighter;
use App\Dto\FighterCounters;
use App\Repository\EffectivenessRepository;
use App\Repository\UnitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MercenaryAdviceController extends AbstractController
{
    public function __construct(
        private readonly EffectivenessRepository $effectivenessRepository,
        private readonly UnitsRepository $unitsRepository
    )
    {
    }

    #[Route('/mercenaries/{fighters}', name: 'mercenary_advice', methods: 'GET')]
    public function number(string $fighters): Response
    {
        $fighterIds = explode(',', $fighters);
        $fighters = $this->unitsRepository->getFightersById($fighterIds);

        return $this->showMercenaryAdvice($fighters);
    }

    /** @param Fighter[] $selectedFighters */
    private function showMercenaryAdvice(array $selectedFighters): Response
    {
        $mercenaries = $this->unitsRepository->getMercenariesSortedByMythiumCost();
        $fighterCounters = [];
        foreach ($selectedFighters as $selectedFighter) {
            $counters = [];
            foreach ($mercenaries as $mercenary) {
                $attackModifier = $this->effectivenessRepository->getEffectiveness($mercenary->attackType, $selectedFighter->armorType);
                $defenseModifier = $this->effectivenessRepository->getEffectiveness($selectedFighter->attackType, $mercenary->armorType);
                if ($attackModifier + $defenseModifier > 0) {
                    $counters[] = new Counter($mercenary, $selectedFighter, $attackModifier, $defenseModifier);
                }
            }

            usort($counters, fn(Counter $counterA, Counter $counterB) => $counterB->getTotalModifier() <=> $counterA->getTotalModifier());
            $fighterCounters[] = new FighterCounters($selectedFighter, $counters);
        }

        return $this->render('mercenary_advice.twig', ['fighterCounters' => $fighterCounters]);
    }
}
