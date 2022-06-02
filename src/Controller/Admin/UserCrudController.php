<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
        {
            return User::class;
        }

    public function configureActions(Actions $actions): Actions
        {
            return $actions;
        }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            //Modifier le titre de la page singulier/pluriel
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            //Titre de la page 
            ->setPageTitle("index", "Yadelair - Administration des Utilisateurs")
            //Nombre d'utilisateurs par page
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
            TextField::new('prenom'),
            TextField::new('pseudo')
                ->hideOnIndex(),
            TextareaField::new('a_propos')
                //je rajoute le wiziwig de CKEditor dans le formulaire
                ->setFormType(CKEditorType::class)
                ->hideOnIndex(),
            NumberField::new('contribution'),
            EmailField::new('email')
                ->setFormTypeOption('disabled', 'disabled')
                ->hideOnForm(),
            UrlField::new('social', 'réseaux sociaux'),
            AssociationField::new('article')
                ->hideOnIndex()

        ];
    }
}
