<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
        {
            return Categorie::class;
        }
    
    public function configureCrud(Crud $crud): Crud
        {
            return $crud
            //Modifier le titre de la page singulier/pluriel
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
            //Titre de la page 
            ->setPageTitle("index" ,"Yadelair - Administration des Catégories")
            //Nombre de contact par page
            ->setPaginatorPageSize(10);
        }



    public function configureFields(string $pageName): iterable
        {
            return [
                IdField::new('id')->hideOnForm()
                ->hideOnIndex(),

                TextField::new('nom', 'titre de la catégorie'),

                TextEditorField::new('description'),

                SlugField::new('slug', 'text de référencement')
                ->setTargetFieldName('nom')
                ->hideOnIndex(),
                
                BooleanField::new('inMenu', 'ajouter au menu'),
            ];
        }

}
