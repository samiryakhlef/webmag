<?php

namespace App\Controller\Command;

use App\Service\ContactService;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\ContactRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as Command;


// j'étends la classe SendContactCommand 
class SendContactCommand extends Command
{
    private $contactRepository;
    private $mailer;
    private $contactService;
    private $userRepository;
    //j'utilise une méthode static pour le default name
    //et je definis le nom de la commande
    protected static $defaultName = 'app:send-contact';

    public function __construct(
        ContactRepository $contactRepository,
        MailerInterface $mailer,
        ContactService $contactService,
        UserRepository $userRepository
    ) {
        $this->contactRepository = $contactRepository;
        $this->mailer = $mailer;
        $this->contactService = $contactService;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //je récupère tous les contacts qui n'ont pas encore été envoyés et en attente d'envoie
        $toSend = $this->contactRepository->findBy(['isSend' => false]);
        //je créer une variable adress pour stocker le mail de l'utilisateur, le nom et le prenom
        $adress = new Address($this->userRepository->getUser()->getEmail(), $this->userRepository->getUser()->getNom() . ' ' . $this->userRepository->getUser()->getPrenom());


        //je boucle sur les contacts
        foreach ($toSend as $mail) {
            //je créer un nouveau mail
            $email = (new Email())
                //l'expediteur
                ->from($mail->getEmail())
                //le destinataire
                ->to($adress)
                //le sujet
                ->subject('Nouveau message de ' . $mail->getNom())
                //le message
                ->text($mail->getMessage());
            //je envoie le mail
            $this->mailer->send($email);
            //je met a jour le isSend en base de données
            $this->contactService->isSend($mail);
        }
        //je retourne un message de confirmation
        return Command::SUCCESS;
    }
}
