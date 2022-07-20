<?php

namespace App\Controller;


use App\Entity\Subscriber;
use App\Form\SubscriberType;
use App\Repository\SubscriberRepository;
use App\Service\NewsletterService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


// Création du controller NewsletterController
class NewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(
        Request $request,
        SubscriberRepository $subscriberRepository,
        NewsletterService $newsletterService,
    ): Response
    {
        //création du formulaire de newsletter
        // $newsletter = new Newsletter();
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldSubscriber = $subscriberRepository->findOneBy(['email' => $subscriber->getEmail()]);
            if(empty($oldSubscriber)) {
                $subscriberRepository->add($subscriber, true);
                $this->addFlash('success', 'Votre demande d\'inscription à notre newsletter à bien été pris en compte !');

                // on envoie un mail de confirmation de l'inscription
                $newsletterService->sendEmailConfirmation($subscriber);
            } elseif(!$oldSubscriber->isActive()) {
                $this->addFlash('success', 'Votre demande d\'inscription à notre newsletter à bien été pris en compte !');

                // on envoie un mail de confirmation de l'inscription
                $newsletterService->sendEmailConfirmation($oldSubscriber);
            } else {
                $this->addFlash('success', 'Vous êtes déjà inscrit à notre newsletter !');
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
            'form' => $form->createView(),
        ]);
    }
    // fonction pour confirmation d'inscription à la newsletter
    #[Route('/newsletter/confirmation/{token}', name: 'app_newsletter_confirm_subscription')]
    public function confirmSubscription(Subscriber $subscriber, SubscriberRepository $subscriberRepository): Response
    {
        //je récupère le service newsletterService
        $subscriber
            ->setIsActive(true)
            ->setActivatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
        ;
        //je persiste le subscriber dans la base de données
        $subscriberRepository->add($subscriber, true);
        
        // je creer un message de confirmation de l'inscription à la newsletter
        $this->addFlash('success', 'Votre inscription à notre newsletter a bien été confirmée !');

        // je redirige vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }
    // fonction pour désinscription à la newsletter
    #[Route('/newsletter/desinscription/{token}', name: 'app_newsletter_unsubscribe')]
    public function unsubscribe(String $token, SubscriberRepository $subscriberRepository): Response
    {
        //je récupère le subscriber dans la base de données à partir du token
        $subscriber = $subscriberRepository->findOneBy(['token' => $token]);

        //je vérifie que le subscriber existe bien
        if(!empty($subscriber)) {
            $subscriberRepository->remove($subscriber, true);
        }
        // je creer un message de confirmation de la désinscription à la newsletter
        $this->addFlash('success', 'Votre demande de désinscription de notre newsletter a bien été prise en compte !');
        // je redirige vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }

}