<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleCrudController extends AbstractCrudController
{
    //j'instancie entity ArticleRepository
    public static function getEntityFqcn(): string
    {
        //je récupère l'articleRepository
        return Article::class;
    }
    //je créé les champs de l'entité que je veus afficher dans le backoffice
    //le champs slug et le createdAt n'apparaissent n'apparaissent que dans la page d'accueil du backoffice
    public function configureFields(string $pageName): iterable
    {
        return [
            //je créé un champs titre
            TextField::new ('titre'),

            //je créé un champs contenu
            TextareaField::new ('contenu'),

            //je créé un champ auteur
            TextField::new ('auteur'),

            //je créé un champs slug et je l'affiche uniquement surl'accueil du back office
            SlugField::new ('slug')->setTargetFieldName('titre'),

            //je créé un champs createdAt et je l'affiche uniquement sur l'accueildu backoffice
            DateTimeField::new ('createdAt')->hideOnForm(),

            //je créé des champs pour stocker mes images ou mes vidéos
            TextField::new ('imageFile')->setFormType(VichImageType::class),

            //je  récupèreles images et je les affiches en miniatures
            ImageField::new ('file')->setBasePath('/uploads/articles/')->onlyOnIndex(),

            AssociationField::new ('categorie'),

            //je créé un bouton pour envoyer des notifications
            BooleanField::new ('notification'),
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
