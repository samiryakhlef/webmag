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
    public function __construct(EntityManagerInterface $manager,
    MailerInterface $mailer)
    {
        $this->manager = $manager;
        $this->mailer = $mailer;
    }



    //je créer une fonction persistNewsletter
    public function persistNewsletter(Newsletter $news): void
    {
        //je met le setIsSend à false car par defaut le message n'est pas envoyé
        $news->setIsSend(false)
            //je met le setCreatedAt à la date du jour
            ->setCreatedAt(new DateTimeImmutable('now'));
        //je persiste le news
        $this->manager->persist($news);
        //je flush en base de données
        $this->manager->flush();
    }

    public function sendNewsletterEmail()
    {
        $email = (new Email())
            ->from('test@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Inscription à la newsletter')
            ->text('Merci pour votre inscription à la newsletter')
            ->html('<p>Vous êtes désormais inscrit à notre newsletter, vous recevrez désormais nos derniers articles</p>');

        $this->mailer->send($email);
    }
}
