<?php

namespace App\Service;

use DateTimeImmutable;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



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

    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('test@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
    }
}
