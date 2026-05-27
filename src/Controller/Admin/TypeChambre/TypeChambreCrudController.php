<?php

namespace App\Controller\Admin\TypeChambre;

use App\Entity\TypeChambre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TypeChambreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeChambre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('nomType', 'Nom du type'),

            IntegerField::new('prixParNuit', 'Prix par nuit'),

            IntegerField::new('capaciteMax', 'Capacité maximale'),
        ];
    }
}