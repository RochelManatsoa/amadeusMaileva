<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // return [
        //     IdField::new('id')->hideOnForm(),
        //     TextField::new('name'),
        //     TextEditorField::new('description'),
        //     AssociationField::new('category'),
        // ];

        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield TextEditorField::new('description')->hideOnIndex();
        yield AssociationField::new('category');
        yield TextField::new('address');
        yield TextField::new('complement')->hideOnIndex();
        yield TextField::new('zipCode');
        yield TextField::new('slug');
        yield TextField::new('city');
        yield TextField::new('country')->hideOnIndex();
    }
}
