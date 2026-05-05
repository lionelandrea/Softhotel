<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ChambreRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route('', name: 'app_reservation_index', methods: ['GET'])]
    #[Route('/admin/reservation', name: 'admin_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        ChambreRepository $chambreRepository,
        ReservationRepository $reservationRepository
    ): Response {
        $chambre = $chambreRepository->find($id);

        if (!$chambre) {
            throw $this->createNotFoundException('Cette chambre est introuvable.');
        }

        $reservation = new Reservation();
        $reservation->setChambre($chambre);
        $reservation->setDateReservation(new \DateTime());
        $reservation->setStatut('En attente');
        $reservation->setMontantTotal(0);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateDebut = $reservation->getDateDebut();
            $dateFin = $reservation->getDateFin();

            if ($dateDebut >= $dateFin) {
                $this->addFlash('danger', 'La date de fin doit être après la date de début.');

                return $this->render('reservation/new.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form,
                    'chambre' => $chambre,
                ]);
            }

            $reservationExistante = $reservationRepository->createQueryBuilder('r')
                ->andWhere('r.chambre = :chambre')
                ->andWhere('r.dateDebut < :dateFin')
                ->andWhere('r.dateFin > :dateDebut')
                ->andWhere('r.statut != :statutAnnule')
                ->setParameter('chambre', $chambre)
                ->setParameter('dateDebut', $dateDebut)
                ->setParameter('dateFin', $dateFin)
                ->setParameter('statutAnnule', 'Annulée')
                ->getQuery()
                ->getResult();

            if (count($reservationExistante) > 0) {
                $this->addFlash('danger', 'Cette chambre est déjà réservée pour cette période.');

                return $this->render('reservation/new.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form,
                    'chambre' => $chambre,
                ]);
            }

            $nombreNuits = $dateDebut->diff($dateFin)->days;
            $prixParNuit = $chambre->getTypeChambre()->getPrixParNuit();
            $montantTotal = $nombreNuits * $prixParNuit;

            $reservation->setMontantTotal($montantTotal);
            $reservation->setStatut('Confirmée');
            $chambre->setDisponible(false);

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_page', [
                'id' => $reservation->getId(),
            ]);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'chambre' => $chambre,
        ]);
    }

    #[Route('/show/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Reservation $reservation,
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepository
    ): Response {
        $ancienneDateDebut = clone $reservation->getDateDebut();
        $ancienneDateFin = clone $reservation->getDateFin();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateDebut = $reservation->getDateDebut();
            $dateFin = $reservation->getDateFin();
            $chambre = $reservation->getChambre();

            if ($dateDebut >= $dateFin) {
                $this->addFlash('danger', 'La date de fin doit être après la date de début.');

                return $this->render('reservation/edit.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form,
                ]);
            }

            $reservationExistante = $reservationRepository->createQueryBuilder('r')
                ->andWhere('r.chambre = :chambre')
                ->andWhere('r.id != :reservationId')
                ->andWhere('r.dateDebut < :dateFin')
                ->andWhere('r.dateFin > :dateDebut')
                ->andWhere('r.statut != :statutAnnule')
                ->setParameter('chambre', $chambre)
                ->setParameter('reservationId', $reservation->getId())
                ->setParameter('dateDebut', $dateDebut)
                ->setParameter('dateFin', $dateFin)
                ->setParameter('statutAnnule', 'Annulée')
                ->getQuery()
                ->getResult();

            if (count($reservationExistante) > 0) {
                $this->addFlash('danger', 'Cette chambre est déjà réservée pour cette période.');

                return $this->render('reservation/edit.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form,
                ]);
            }

            $nombreNuits = $dateDebut->diff($dateFin)->days;
            $prixParNuit = $chambre->getTypeChambre()->getPrixParNuit();

            $reservation->setMontantTotal($nombreNuits * $prixParNuit);

            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Reservation $reservation,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $reservation->getChambre()?->setDisponible(true);

            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/annuler/{id}', name: 'app_reservation_annuler', methods: ['POST'])]
    public function annuler(
        Request $request,
        Reservation $reservation,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('annuler'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $reservation->setStatut('Annulée');
            $reservation->getChambre()?->setDisponible(true);

            $entityManager->flush();

        $this->addFlash('success', 'Réservation annulée avec succès.');
    }

          return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}