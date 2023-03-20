<?php

namespace App\Controller\Admin;

use App\Entity\Recipient;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipient::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('recipient_id'),
            TextField::new('custom_id'),
            TextField::new('status'),
            TextField::new('archive_url'),
            AssociationField::new('send')->hideOnForm(),
        ];
    }
}
