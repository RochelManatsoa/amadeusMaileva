<?php

namespace App\Controller\Admin;

use App\Entity\Envoi;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

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
            TextField::new('custom_id'),
            TextField::new('envoi_id'),
            TextField::new('status'),
            NumberField::new('document_count'),
            CollectionField::new('documents')->hideOnForm(),
        ];
    }
}
