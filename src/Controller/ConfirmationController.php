<?php

namespace App\Controller;

use App\Entity\Paiement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ConfirmationController extends AbstractController
{
    #[Route('/confirmation/{id}', name: 'app_confirmation')]
    public function index(Paiement $paiement): Response
    {
        return $this->render('confirmation/index.html.twig', [
            'paiement' => $paiement,
        ]);
    }
}