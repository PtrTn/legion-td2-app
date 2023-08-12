<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Counter;
use App\Dto\Fighter;
use App\Dto\WaveCounters;
use App\Form\UnitFormType;
use App\Repository\EffectivenessRepository;
use App\Repository\UnitsRepository;
use App\Repository\WavesRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SubmitFormController extends AbstractController
{
    public function __construct(
        private readonly WavesRepository $wavesRepository,
        private readonly EffectivenessRepository $effectivenessRepository,
        private readonly UnitsRepository $unitsRepository
    )
    {

    }

    #[Route('/', name: 'form_submit', methods: 'POST')]
    public function number(Request $request): Response
    {
        $form = $this->createForm(UnitFormType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->redirectToRoute('form_show');
        }
        if (!$form->isValid()) {
            return $this->redirectToRoute('form_show');
        }

        $selectedUnits = $form->getData()['units'] ?? [];

        if ($form->getClickedButton() && $form->getClickedButton()->getName() === 'fighterAdvice') {
            return $this->showFighterAdvice($selectedUnits);
        }
        if ($form->getClickedButton() && $form->getClickedButton()->getName() === 'mercenaryAdvice') {
            return $this->showMercenaryAdvice($selectedUnits);
        }

        throw new Exception('Unable to handle request');
    }

    /** @param Fighter[] $selectedUnits */
    private function showFighterAdvice(array $selectedUnits): Response
    {
        $waves = $this->wavesRepository->getAll();
        $waveCounters = [];
        foreach ($waves as $wave) {
            $counters = [];
            foreach ($selectedUnits as $selectedUnit) {
                $attackModifier = $this->effectivenessRepository->getEffectiveness($selectedUnit->attackType, $wave->unit->armorType);
                $defenseModifier = $this->effectivenessRepository->getEffectiveness($wave->unit->attackType, $selectedUnit->armorType);
                if ($attackModifier + $defenseModifier > 0) {
                    $counters[] = new Counter($selectedUnit, $wave->unit, $attackModifier, $defenseModifier);
                }
            }

            usort($counters, fn(Counter $counterA, Counter $counterB) => $counterB->getTotalModifier() <=> $counterA->getTotalModifier());
            $waveCounters[] = new WaveCounters($wave, $counters);
        }

        return $this->render('fighter_advice.twig', ['waveCounters' => $waveCounters]); // todo, show per fighter instead.
    }

    /** @param Fighter[] $selectedUnits */
    private function showMercenaryAdvice(array $selectedUnits): Response
    {
        $mercenaries = $this->unitsRepository->getMercenariesSortedByMythiumCost();
        foreach ($selectedUnits as $selectedUnit) {
            foreach ($mercenaries as $mercenary) {
                $attackModifier = $this->effectivenessRepository->getEffectiveness($mercenary->attackType, $selectedUnit->armorType);
                $defenseModifier = $this->effectivenessRepository->getEffectiveness($selectedUnit->attackType, $mercenary->armorType);
                if ($attackModifier + $defenseModifier > 0) {
                    $counters[] = new Counter($mercenary, $selectedUnit, $attackModifier, $defenseModifier);
                }
            }
        }

        dump($counters);
        return $this->render('mercenary_advice.twig', ['counters' => $counters]);
    }
}
