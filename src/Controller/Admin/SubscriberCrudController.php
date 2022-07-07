<?php

namespace App\Controller\Admin;

use App\Entity\Newsletter;
use App\Entity\Subscriber;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class SubscriberCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Subscriber::class;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Abonné')
            ->setEntityLabelInPlural('Abonnés')
            ->setPageTitle("index","Yadelair - Abonnés")
            ->setPaginatorPageSize(10)
            ->setDateTimeFormat('dd MMMM yyyy');
    }
    public function configureActions(Actions $actions): Actions
{
    return $actions
        // ...
        ->add(Crud::PAGE_INDEX, Action::DETAIL, ['icon' => 'fa fa-eye'])
        
        ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER);
}

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnDetail()
                ->hideOnIndex()
                ->hideOnForm(),
            EmailField::new('email', 'E-mail'),
           // BooleanField::new('isActive', 'Envoyé'),
            DateTimeField::new('createdAt','Inscrit le:')
                ->setFormat('dd MMMM yyyy')
                ->setTimezone('Europe/Paris'),
            DateTimeField::new('updatedAt', 'Mis à jour le:')
                ->setFormat('dd MMMM yyyy')
                ->setTimezone('Europe/Paris'),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Newsletter) return;
        $entityInstance
            ->setCreatedAt(new \DateTimeImmutable('now'));
    }
}
