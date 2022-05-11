<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\EventSubsriber\EasyAdminSubscriber;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
            //je créé un champ slug qui n'apparait que dans la page d'acceuil du backoffice
            TextField::new('slug')->hideOnForm(),
            //je créé un champ contenu
            TextareaField::new('contenu'),
            //je créé un champ createdAt qui n'apparait que dans la page d'acceuil du backoffice
            DateTimeField::new('createdAt')->hideOnForm(),
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
