<?php

namespace App\EventSubscriber;

use App\Entity\BlogPost;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    // je vais chercher le sluggerInterface pour générer un slug
    public $slugger;
    public $security;

    public function __construct(SluggerInterface $slugger, Security $security)
    {
        $this->slugger = $slugger;
        $this->security = $security;
    }
    //je créer une fonction pour m'abonner aux evenements de l'entité
    public static function getSubscribedEvents(): array
    {
        return [
            //j'écoute l'evenement avant qu'il soit persisté et si un evenement est persisté
            //je vais l'envoyer dans la fonction 'setBlogPostSlugAndDateAndUser'
            BeforeEntityPersistedEvent::class => 'setDateAndUser',
        ];
    }

    //je récupère un évenement et je le traite dans ma fonction
    public function setDateAndUser(BeforeEntityPersistedEvent $event)
    {

        //je récupère l'entité qui viens d'être persistée
        $entity = $event->getEntityInstance();

        //je cérifie que l'entité est une instance de BlogPost
        if ($entity instanceof BlogPost) {
            return;
        }

        //je génère un slug
        $slug = $this->slugger->slug($entity->getTitre());
        //je sette le slug et je la stock dans $slug
        $entity->setSlug($slug);

        //je génère une date du jour
        $now = new \DateTimeImmutable('now');
        //je sette la date du jour et je la stock dans $now
        $entity->setCreatedAt($now);

        //je récupère l'utilisateur connecté
        $user = $this->security->getUser();
        //je sette l'utilisateur connecté et je la stock dans $user
        $entity->setUser($user);
    }
}
