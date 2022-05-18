<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{

    const ARTICLE_UPLOAD_DIR = VichImageType::class;
    const ARTICLE_BASE_PATH = 'uploads/articles';
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
            TextField::new ('titre','Titre de l\'article'),

            //je créé un champs contenu
            TextEditorField::new ('contenu','Contenu de l\'article'),

            //je créé un champ auteur
            TextField::new ('auteur','Auteur de l\'article'),

            //je créé un champs slug et je l'affiche uniquement surl'accueil du back office
            SlugField::new ('slug','text de référencement')->setTargetFieldName('titre'),

            //je créé un champs createdAt et je l'affiche uniquement sur l'accueildu backoffice
            DateTimeField::new ('createdAt', 'Date de création')
                ->hideOnForm(),

            //je créé des champs pour stocker mes images ou mes vidéos
            TextField::new ('imageFile')
                ->setFormType(self::ARTICLE_UPLOAD_DIR)
                ->hideOnIndex(),

            //je  récupèreles images et je les affiches en miniatures
            ImageField::new ('file')
                ->setBasePath(self::ARTICLE_BASE_PATH)
                ->onlyOnIndex()
                ->setSortable(false),

            AssociationField::new ('categorie')
                ->setFormType(CategorieCrudController::class)
                ->setSortable(false),

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

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if(!$entityInstance instanceof Article) return;
        $entityInstance
        ->setCreatedAt(new \DateTimeImmutable())
        ->setUser($this->getUser());
        
        //je persiste et je flush en base de données
        parent::persistEntity($em, $entityInstance);
    }
}
