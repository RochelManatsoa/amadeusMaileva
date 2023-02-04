<?php

namespace App\Controller\Admin;

use App\Entity\StripeTransaction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StripeTransactionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StripeTransaction::class;
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
