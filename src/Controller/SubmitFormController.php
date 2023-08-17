<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Fighter;
use App\Form\UnitFormType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SubmitFormController extends AbstractController
{
    public function __construct(
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
        $fighterIds = array_map(fn(Fighter $fighter) => $fighter->getShortIdentifier(), $selectedUnits);
        $fighterIds = implode(',', $fighterIds);

        if ($form->getClickedButton() && $form->getClickedButton()->getName() === 'fighterAdvice') {
            return $this->redirectToRoute('fighter_advice', ['fighters' => $fighterIds]);
        }
        if ($form->getClickedButton() && $form->getClickedButton()->getName() === 'mercenaryAdvice') {
            return $this->redirectToRoute('mercenary_advice', ['fighters' => $fighterIds]);
        }

        throw new Exception('Unable to handle request');
    }
}
