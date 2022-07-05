<?php

namespace App\Service;

use DateTimeImmutable;
use App\Entity\Newsletter;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NewsletterService
{
    //je stocke l'entity manager interface dans une variable privée
    private $manager;
    private $mailer;
    private $router;

    //je créer un constructeur pour initialiser ma variable privée
    public function __construct(
    EntityManagerInterface $manager,
    MailerInterface $mailer,
    TokenStorageInterface $tokenStorage,
    UrlGeneratorInterface $router
    ) {
        $this->manager = $manager;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }


    //je créer une fonction persistNewsletter
    public function persistNewsletter(Newsletter $newsletter): void
    {
         //je creer un token de validation de l'email
        $token = hash('sha256', uniqid());
         //je vérifie le token de validation de l'email
        $newsletter->setValidationToken($token);
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
            ->text('Vous êtes désormais inscrit à notre newsletter, vous recevrez désormais nos derniers articles, vous pouvez vous désinscrire à tout moment en cliquant sur le lien ci-dessous :
            ')
            ->html($url = $this->router->generate('app_newsletter_unsubscribe', [
                'token' => $newsletter->getValidationToken(),
            ], UrlGeneratorInterface ::ABSOLUTE_URL));
            
            $this->mailer->send($email);
        }


     //je créer une fonction unsubscribeNewsletter pour supprimer en base de données
    public function unsubscribeNewsletter(Newsletter $newsletter, $token): void
        {
            //je récupère le token de validation de l'email
            $newsletter->setValidationToken($token);

            //je supprime le newsletter de la base de données
            $this->manager->remove($newsletter);
            //je flush en base de données
            $this->manager->flush();
            
        }
}
