<?php

namespace App\Service;

use DateTimeImmutable;
use App\Entity\Contact;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;



class ContactService
{
    //je stocke l'entity manager interface dans une variable privée
    private $manager;
    private $mailer;

    //je créer un constructeur pour initialiser ma variable privée
    public function __construct(EntityManagerInterface $manager,
    MailerInterface $mailer)
    {
        $this->manager = $manager;
        $this->mailer = $mailer;

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

    public function sendEmailContact(Contact $contact)
    {
        $email = (new Email())
            ->from('yriche@labconseil.fr')
            ->to($contact->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject($contact->getSujet())
            ->text($contact->getMessage());

            $this->mailer->send($email);
    }
}
