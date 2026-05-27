<?php

namespace App\Controller\Admin\Reservation;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('dateDebut', 'Date début'),
            DateField::new('dateFin', 'Date fin'),
            DateField::new('dateReservation', 'Date réservation'),
            TextField::new('statut', 'Statut'),
            IntegerField::new('montantTotal', 'Montant total'),
            AssociationField::new('client', 'Client'),
            AssociationField::new('chambre', 'Chambre'),
        ];
    }
}