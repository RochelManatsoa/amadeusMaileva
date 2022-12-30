<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Document::class;
    }

    public function configureFields(string $pageName): iterable
    {        
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('doc_id'),
            NumberField::new('priority'),
            TextField::new('name'),
            TextField::new('type'),
            NumberField::new('pages_count'),
            NumberField::new('sheets_count'),
            NumberField::new('size'),
            NumberField::new('converted_size'),
            AssociationField::new('send')->hideOnForm(),
        ];
    }
}
