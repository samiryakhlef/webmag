<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BlogPostCrudController extends AbstractCrudController
{
    // j'instancie l'entité BlogpostRepository
    public static function getEntityFqcn(): string
    {
        //je récupère l'entityRepository Blogpost
        return BlogPost::class;
    }

    //je créé les champs de l'entité que je veus afficher dans le backoffice
    //le champs slug et le createdAt n'apparaissent n'apparaissent que dans la page d'acceuil du backoffice
    public function configureFields(string $pageName): iterable
    {
        return [
            //je créé un champ titre
            TextField::new('titre'),
            //je créer un champs auteur 
            TextField::new('auteur'),
            //je créé un champ slug qui n'apparait que dans la page d'acceuil du backoffice
            SlugField::new('slug')->setTargetFieldName('titre')->hideOnIndex(),
            //je créé un champ contenu
            TextareaField::new('contenu'),
            //je créé un champ createdAt qui n'apparait que dans la page d'acceuil du backoffice
            DateTimeField::new('createdAt','Date de création')
        ];
    }
    //je créé une fonction "configureCrud" qui me permet de classer les posts et les dates de création
    //par ordre décroissant
    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            //je définis l'ordre par default
            ->setDefaultSort(['createdAt' => 'DESC']);
    }
}
