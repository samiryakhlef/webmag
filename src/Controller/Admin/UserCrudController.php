<?php

namespace App\Controller\Admin;

use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    const ARTICLE_UPLOAD_DIR = VichImageType::class;
    const ARTICLE_BASE_PATH = 'uploads/profil';

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
            TextField::new ('imageFile')
                ->setFormType(self::ARTICLE_UPLOAD_DIR)
                ->hideOnIndex()
                ->hideOnForm(),

        //je  récupèreles images et je les affiches en miniatures
            ImageField::new ('file', 'Photo de profil')
                ->setBasePath(self::ARTICLE_BASE_PATH)
                ->hideOnIndex()
                ->setSortable(false),
            AssociationField::new('article')
                ->hideOnIndex()

        ];
    }
}
