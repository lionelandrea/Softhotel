<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Repository\PaiementRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/paiement')]
final class PaiementController extends AbstractController
{
    public function __construct(private HttpClientInterface $client) {}

    #[Route('', name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository): Response
    {
        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiementRepository->findAll(),
        ]);
    }

    #[Route('/reservation/{id}', name: 'app_paiement_page', methods: ['GET'])]
    public function paiementPage(int $id, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation introuvable.');
        }

        return $this->render('paiement/payment_page.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/confirmer/{id}', name: 'app_paiement_confirmer', methods: ['POST'])]
    public function confirmerPaiement(
        int $id,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation introuvable.');
        }

        $paiement = new Paiement();
        $paiement->setReservation($reservation);
        $paiement->setMontant($reservation->getMontantTotal());
        $paiement->setDatePaiement(new \DateTime());
        $paiement->setReferencePaypal('Paiement manuel');

        $reservation->setStatut('Payée');

        $entityManager->persist($paiement);
        $entityManager->flush();

        $this->addFlash('success', 'Paiement manuel confirmé avec succès.');

        return $this->redirectToRoute('app_reservation_show', [
            'id' => $reservation->getId(),
        ]);
    }

    #[Route('/paypal/create/{id}', name: 'app_paypal_create', methods: ['POST'])]
    public function createPaypalOrder(int $id, ReservationRepository $reservationRepository): RedirectResponse
    {
        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation introuvable.');
        }

        $token = $this->getPaypalAccessToken();
        $amount = number_format($reservation->getMontantTotal(), 2, '.', '');

        $response = $this->client->request('POST', $_ENV['PAYPAL_BASE_URL'].'/v2/checkout/orders', [
            'auth_bearer' => $token,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => 'reservation_'.$reservation->getId(),
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => $amount,
                    ],
                ]],
                'application_context' => [
                    'return_url' => $this->generateUrl('app_paypal_success', [
                        'id' => $reservation->getId(),
                    ], 0),
                    'cancel_url' => $this->generateUrl('app_paiement_page', [
                        'id' => $reservation->getId(),
                    ], 0),
                ],
            ],
        ]);

        $data = $response->toArray();

        foreach ($data['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return $this->redirect($link['href']);
            }
        }

        $this->addFlash('danger', 'Impossible de créer le paiement PayPal.');

        return $this->redirectToRoute('app_paiement_page', [
            'id' => $reservation->getId(),
        ]);
    }

    #[Route('/paypal/success/{id}', name: 'app_paypal_success', methods: ['GET'])]
    public function paypalSuccess(
        int $id,
        Request $request,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation introuvable.');
        }

        $paypalOrderId = $request->query->get('token');

        if (!$paypalOrderId) {
            $this->addFlash('danger', 'Paiement PayPal invalide.');

            return $this->redirectToRoute('app_paiement_page', [
                'id' => $reservation->getId(),
            ]);
        }

        $token = $this->getPaypalAccessToken();

        $response = $this->client->request(
            'POST',
            $_ENV['PAYPAL_BASE_URL'].'/v2/checkout/orders/'.$paypalOrderId.'/capture',
            [
                'auth_bearer' => $token,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        $data = $response->toArray(false);

        if (($data['status'] ?? null) !== 'COMPLETED') {
            $this->addFlash('danger', 'Le paiement PayPal n’a pas été confirmé.');

            return $this->redirectToRoute('app_paiement_page', [
                'id' => $reservation->getId(),
            ]);
        }

        $paiement = new Paiement();
        $paiement->setReservation($reservation);
        $paiement->setMontant($reservation->getMontantTotal());
        $paiement->setDatePaiement(new \DateTime());
        $paiement->setReferencePaypal($paypalOrderId);

        $reservation->setStatut('Payée');

        $entityManager->persist($paiement);
        $entityManager->flush();

        $this->addFlash('success', 'Paiement PayPal confirmé avec succès.');

        return $this->redirectToRoute('app_reservation_show', [
            'id' => $reservation->getId(),
        ]);
    }

    private function getPaypalAccessToken(): string
    {
        $response = $this->client->request('POST', $_ENV['PAYPAL_BASE_URL'].'/v1/oauth2/token', [
            'auth_basic' => [
                $_ENV['PAYPAL_CLIENT_ID'],
                $_ENV['PAYPAL_CLIENT_SECRET'],
            ],
            'body' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = $response->toArray();

        return $data['access_token'];
    }
}