<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Matchup;
use App\Dto\Fighter;
use App\Dto\MercenaryMatchups;
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

        return $this->render('mercenary_advice.twig', ['mercenaryMatchups' => $mercenaryMatchups]);
    }
}
