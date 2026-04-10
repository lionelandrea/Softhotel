<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SoftHotel Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Gestion');

        yield MenuItem::linkToRoute('Clients', 'fa fa-users', 'app_client_index');
        yield MenuItem::linkToRoute('Chambres', 'fa fa-bed', 'app_chambre_index');
        yield MenuItem::linkToRoute('Réservations', 'fa fa-calendar-check', 'app_reservation_new', ['id' => 1]);
        yield MenuItem::linkToRoute('Paiements', 'fa fa-money-bill', 'app_paiement', ['id' => 1]);
    }
}