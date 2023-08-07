<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UnitFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SubmitFormController extends AbstractController
{
    #[Route('/success', name: 'form_submit', methods: 'POST')]
    public function number(Request $request): Response
    {
        $form = $this->createForm(UnitFormType::class, null, ['action' => $this->generateUrl('form_submit')]);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->redirectToRoute('form_show');
        }
        if (!$form->isValid()) {
            return $this->redirectToRoute('form_show');
        }

        $data = $form->getData();

        return $this->render('form_submit.twig', ['data' => $data]);
    }
}
