<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Counter;
use App\Dto\WaveCounters;
use App\Form\UnitFormType;
use App\Repository\EffectivenessRepository;
use App\Repository\WavesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SubmitFormController extends AbstractController
{
    public function __construct(
        private readonly WavesRepository $wavesRepository,
        private readonly EffectivenessRepository $effectivenessRepository,
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

        $defensiveUnits = $form->getData()['units'] ?? [];
        $waves = $this->wavesRepository->getAll();

        $waveCounters = [];
        foreach ($waves as $wave) {
            $counters = [];
            foreach ($defensiveUnits as $defensiveUnit) {
                $attackModifier = $this->effectivenessRepository->getEffectiveness($defensiveUnit->attackType, $wave->unit->armorType);
                $defenseModifier = $this->effectivenessRepository->getEffectiveness($wave->unit->attackType, $defensiveUnit->armorType);
                if ($attackModifier + $defenseModifier > 0) {
                    $counters[] = new Counter($defensiveUnit, $wave->unit, $attackModifier, $defenseModifier);
                }
            }

            usort($counters, fn(Counter $counterA, Counter $counterB) => $counterB->getTotalModifier() <=> $counterA->getTotalModifier());
            $waveCounters[] = new WaveCounters($wave, $counters);
        }

        return $this->render('form_submit.twig', ['waveCounters' => $waveCounters]);
    }
}
