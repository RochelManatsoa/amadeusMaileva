<?php

namespace App\Controller\Admin;

use App\Entity\ApiExchange;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ApiExchangeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ApiExchange::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('type'),
            TextEditorField::new('params'),
            TextEditorField::new('response'),
        ];
    }
}
