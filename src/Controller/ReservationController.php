<?php

namespace App\Controller;

use App\Entity\Chambre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation_new')]
    public function new(Chambre $chambre): Response
    {
        return $this->render('reservation/new.html.twig', [
            'chambre' => $chambre,
        ]);
    }
}