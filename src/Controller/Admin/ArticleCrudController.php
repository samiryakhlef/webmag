<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\QueryBuilder;
use App\Service\ArticleService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{

    const ARTICLE_UPLOAD_DIR = VichImageType::class;
    const ARTICLE_BASE_PATH = 'uploads/articles';
    const VIDEO_BASE_PATH = 'uploads/video';
    //j'instancie entity ArticleRepository
    public static function getEntityFqcn(): string
        {
            //je récupère l'articleRepository
            return Article::class;
        }
        
    public function createIndexQueryBuilder(SearchDto $searchDto, 
    EntityDto $entityDto, 
    FieldCollection $fields, 
    FilterCollection $filters): QueryBuilder
        {
            $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

            if ($this->isGranted('ROLE_ADMIN')) {
                return $queryBuilder;
            }

            $queryBuilder
                ->andWhere('entity.user = :user')
                ->setParameter('user', $this->getUser());
            return $queryBuilder;
        }


     //je créé une fonction "configureCrud" pour configurer mon CRUD
    public function configureCrud(Crud $crud): Crud
        {
            return $crud
            //je définis l'ordre par default
                ->setDefaultSort(['createdAt' => 'DESC'])
                //Modifier le titre de la page singulier/pluriel
                ->setEntityLabelInSingular('Article')
                ->setEntityLabelInPlural('Articles')
            //Titre de la page 
                ->setPageTitle("index" ,"Yadelair - Administration des Articles")
            //Nombre d'utilisateurs par page
                ->setPaginatorPageSize(10)
                //j'ajoute le wiziwig de CKEditor
                ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
        }
    //je créé les champs de l'entité que je veus afficher dans le backoffice
    //le champs slug et le createdAt n'apparaissent n'apparaissent que dans la page d'accueil du backoffice
    public function configureFields(string $pageName): iterable
        {
            $config = [
                    //je créé un champs titre
                    TextField::new ('titre'),

                    //je créé un champs contenu
                    TextEditorField::new ('contenu','Contenu')
                    //je rajoute le wiziwig de CKEditor dans le formulaire
                    ->setFormType(CKEditorType::class, 'ROLE_ADMIN, ROLE_USER'),

                    //je créé un champ auteur
                    TextField::new ('auteur','Auteur'),

                    //je créé un champs slug et je l'affiche uniquement surl'accueil du back office
                    SlugField::new ('slug','texte de référencement')
                    ->setTargetFieldName('titre')
                    ->hideOnIndex(),
                    

                    //je créé un champs createdAt et je l'affiche uniquement sur l'accueildu backoffice
                    DateTimeField::new ('createdAt', 'Date de création')
                        ->hideOnForm(),

                    //je créé des champs pour stocker mes images 
                    Field::new ('imageFile','image')
                        ->setFormType(self::ARTICLE_UPLOAD_DIR)
                        ->hideOnIndex(),

                    //je créer un champs pour l'upload de vidéo
                    Field::new('videoFile', 'vidéo')
                    ->setFormType(self::ARTICLE_UPLOAD_DIR)
                    ->hideOnIndex(),

                    //je  récupèreles images et je les affiches en miniatures
                    ImageField::new ('file', 'Images')
                        ->setBasePath(self::ARTICLE_BASE_PATH)
                        ->onlyOnIndex()
                        ->setSortable(false),
                    
                    //je  récupèreles les vidéos et je les affiches en miniatures
                    ImageField::new ('videoName', 'Vidéo')
                        ->setBasePath(self::VIDEO_BASE_PATH)
                        ->onlyOnIndex(),

                    AssociationField::new ('categorie')
                    ->hideOnIndex(),     
                ];

                    if(!$this->isGranted('ROLE_ADMIN'))
                    {
                        $config[] = BooleanField::new('published', 'Publié')
                            ->hideOnForm()
                            ->renderAsSwitch();
                    }else{
                        $config[] = BooleanField::new('published', 'Publié ?');
                    }

                    return $config;
        }
    
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
        {
                if(!$entityInstance instanceof Article) return;

                $entityInstance
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUser($this->getUser());
                
                //je persiste et je flush en base de données et j'envoie un email
                parent::persistEntity($em, $entityInstance);

                if($entityInstance->isPublished())
                    {
                        $this->articleService->sendEmailArticle();
                    }
        }

    public function __construct(private ArticleService $articleService){} 
    }


