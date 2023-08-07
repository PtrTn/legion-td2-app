<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LuckyController extends AbstractController
{
    #[Route('/lucky/number')]
    public function number(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $data = $form->getData();

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('success');
        }


        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
            'form' => $form,
        ]);
    }
}
