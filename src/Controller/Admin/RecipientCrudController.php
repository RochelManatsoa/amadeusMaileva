<?php

namespace App\Controller\Admin;

use App\Entity\Recipient;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipient::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
