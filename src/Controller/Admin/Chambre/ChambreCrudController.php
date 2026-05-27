<?php

namespace App\Controller\Admin\Chambre;

use App\Entity\Chambre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class ChambreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Chambre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            IntegerField::new('numeroChambre', 'Numéro de chambre'),

            AssociationField::new('typeChambre', 'Type de chambre'),

            BooleanField::new('disponible', 'Disponible'),

            ImageField::new('image', 'Image')
                ->setBasePath('uploads/chambres')
                ->setUploadDir('public/uploads/chambres')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->onlyOnForms(),
        ];
    }
}