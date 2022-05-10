<?php

namespace App\Service;

use DateTimeImmutable;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;


class ContactService
{
    //je stocke l'entity manager interface dans une variable privée
    private $manager;

    //je créer un constructeur pour initialiser ma variable privée
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    //je créer une fonction persistContact
    public function persistContact(Contact $contact): void
    {
        //je met le setIsSend à false car par defaut le message n'est pas envoyé
        $contact->setIsSend(false)
            //je met le setCreatedAt à la date du jour
            ->setCreatedAt(new DateTimeImmutable('now'));
        //je persiste le contact
        $this->manager->persist($contact);
        //je flush en base de données
        $this->manager->flush();
    }


    //envoie de mail de réponse automatique
    //je créer une fonction isSend et je passe en paramètre   contact
    public function isSend(Contact $contact): void
    {
        //je set le setIsSend à true
        $contact->setIsSend(true);
        //je persiste le contact
        $this->manager->persist($contact);
        //je flush en base de données
        $this->manager->flush();
    }
}
