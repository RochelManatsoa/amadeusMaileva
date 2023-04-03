<?php

namespace App\Controller\Admin;

use App\Entity\Envoi;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EnvoiCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Envoi::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('creationDate')->setFormat('dd/MM/YYYY hh:mm aaa'),
            TextField::new('custom_id'),
            TextField::new('envoi_id'),
            TextField::new('status'),
            NumberField::new('document_count'),
            CollectionField::new('documents')->hideOnForm(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('status')
            ->add('creationDate')
        ;
    }
}
