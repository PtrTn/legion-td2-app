<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UnitFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ShowFormController extends AbstractController
{
    #[Route('/', name: 'form_show', methods: 'GET')]
    public function number(): Response
    {
        $form = $this->createForm(UnitFormType::class, null, ['action' => $this->generateUrl('form_submit')]);

        return $this->render('form_show.twig', [
            'form' => $form,
        ]);
    }
}
