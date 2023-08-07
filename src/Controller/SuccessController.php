<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SuccessController extends AbstractController
{
    #[Route('/success', name: 'success')]
    public function number(): Response
    {
        return $this->render('lucky/success.html.twig');
    }
}
