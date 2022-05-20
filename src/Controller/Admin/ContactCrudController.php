<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        //Modifier le titre de la page singulier/pluriel
            ->setEntityLabelInSingular('Contact')
            ->setEntityLabelInPlural('Conctacts')
        //Titre de la page 
            ->setPageTitle("index" ,"Yadelair - administration des demandes de Contact")
        //Nombre de contact par page
            ->setPaginatorPageSize(10);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm()
            ->hideOnIndex(),
            TextField::new('nom'),
            TextEditorField::new('message'),
            EmailField::new('email'),
            BooleanField::new('isSend')
            ->hideOnForm(),
            DateTimeField::new('createdAt'),
        ];
    }
    
}
