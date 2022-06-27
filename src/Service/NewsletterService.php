<?php

namespace App\Service;

use DateTimeImmutable;
use App\Entity\Newsletter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



class NewsletterService
{
    //je stocke l'entity manager interface dans une variable privée
    private $manager;
    private $mailer;

    //je créer un constructeur pour initialiser ma variable privée
    public function __construct(
    EntityManagerInterface $manager,
    MailerInterface $mailer
    )
    {
        $this->manager = $manager;
        $this->mailer = $mailer;
    }



    //je créer une fonction persistNewsletter
    public function persistNewsletter(Newsletter $newsletter): void
    {
        //je met le setIsSend à false car par defaut le message n'est pas envoyé
        $newsletter->setIsSend(false)
            //je met le setCreatedAt à la date du jour
            ->setCreatedAt(new DateTimeImmutable('now'));
        //je persiste le newsletter
        $this->manager->persist($newsletter);
        //je flush en base de données
        $this->manager->flush();
    }
    // envoyer un email de confirmation d'inscription à la newsletter
    public function sendNewsletterEmail(Newsletter $newsletter)
    {
        $email = (new Email())
            ->from('yriche@lab-conseil.fr')
            ->to($newsletter->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Confirmation d\'inscription à la newsletter')
            ->text('Vous êtes désormais inscrit à notre newsletter, vous recevrez désormais nos derniers articles');

        $this->mailer->send($email);
    }
}
