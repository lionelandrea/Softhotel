<?php

namespace App\Controller;

use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PaiementController extends AbstractController
{
    #[Route('/paiement/{id}', name: 'app_paiement')]
    public function index(Reservation $reservation): Response
    {
        $prixParNuit = $reservation->getChambre()?->getTypeChambre()?->getPrixParNuit() ?? 0;

        $nbJours = 0;
        if ($reservation->getDateDebut() && $reservation->getDateFin()) {
            $nbJours = $reservation->getDateDebut()->diff($reservation->getDateFin())->days;
        }

        $total = $prixParNuit * $nbJours;

        return $this->render('paiement/index.html.twig', [
            'reservation' => $reservation,
            'prixParNuit' => $prixParNuit,
            'nbJours' => $nbJours,
            'total' => $total,
        ]);
    }
}