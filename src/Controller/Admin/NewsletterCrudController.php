<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Newsletter;
use App\Service\NewsletterService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NewsletterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
        {
            return Newsletter::class;
        }
    public function configureCrud(Crud $crud): Crud
        {
            return $crud
            //Modifier le titre de la page singulier/pluriel
                ->setEntityLabelInSingular('Newsletter')
                ->setEntityLabelInPlural('Newsletters')
            //Titre de la page 
                ->setPageTitle("index" ,"Yadelair - administration de la newsletter")
            //Nombre de contact par page
                ->setPaginatorPageSize(10)
            // je mets la date et l'heure au bon format
                ->setDateTimeFormat('d/m/y');
        }
    
        public function configureFields(string $pageName): iterable
        {
            return [
                IdField::new('id')
                ->hideOnDetail()
                ->hideOnIndex(),
                EmailField::new('email', 'E-mail'),
                BooleanField::new('is_send', 'Envoyé'),
                DateTimeField::new('createdAt', 'Inscrit le:'),
            ];
        }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
        {
            if(!$entityInstance instanceof Newsletter) return;
            $entityInstance
            ->setCreatedAt(new \DateTimeImmutable('now'));  

            if($entityInstance->isIsSend())
            {
                $this->newsletterService->sendNewsletterEmail();
            }
        }
    public function __construct(private NewsletterService $newsletterService,){} 
}