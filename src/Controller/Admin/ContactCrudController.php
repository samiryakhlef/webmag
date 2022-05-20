<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use phpDocumentor\Reflection\Types\Boolean;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
            ->setPaginatorPageSize(10)
        //j'ajoute le wiziwig de CKEditor
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm()
            ->hideOnIndex(),
            TextField::new('nom'),
            TextEditorField::new('message')
            //je rajoute le wiziwig de CKEditor dans le formulaire
            ->setFormType(CKEditorType::class),
            EmailField::new('email'),
            BooleanField::new('isSend')
            ->hideOnForm(),
            DateTimeField::new('createdAt'),
        ];
    }
    
}
