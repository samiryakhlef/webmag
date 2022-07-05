<?php

namespace App\Controller;


use App\Entity\Newsletter;
use App\Entity\Subscriber;
use App\Form\NewsletterType;
use App\Form\SubscriberType;
use App\Repository\SubscriberRepository;
use App\Service\NewsletterService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(
        Request $request,
        SubscriberRepository $subscriberRepository,
        NewsletterService $newsletterService,
    ): Response
    {
        // $newsletter = new Newsletter();
        $subscriber = new Subscriber();
        // $form = $this->createForm(NewsletterType::class, $newsletter);
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //je récupère les données du formulaire
            // $newsletter = $form->getData();
            //je récupère ma fonction persistContact pour envoyer en base de données
            // $newsletterService-> persistNewsletter($newsletter);
            // je récupère ma fonction sendNewsletterEmail pour envoyer un email
            // $newsletterService->sendNewsletterEmail($newsletter, $newsletter->getValidationToken());
            //je redirige ma vue vers la page de contact
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

    #[Route('/newsletter/confirmation/{token}', name: 'app_newsletter_confirm_subscription')]
    public function confirmSubscription(Subscriber $subscriber, SubscriberRepository $subscriberRepository): Response
    {
        //je récupère le service newsletterService
        $subscriber
            ->setIsActive(true)
            ->setActivatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
        ;
        $subscriberRepository->add($subscriber, true);
        
        $this->addFlash('success', 'Votre inscription à notre newsletter a bien été confirmée !');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/newsletter/desinscription/{token}', name: 'app_newsletter_unsubscribe')]
    public function unsubscribe(String $token, SubscriberRepository $subscriberRepository): Response
    {
        $subscriber = $subscriberRepository->findOneBy(['token' => $token]);

        if(!empty($subscriber)) {
            $subscriberRepository->remove($subscriber, true);
        }
        
        $this->addFlash('success', 'Votre demande de désinscription de notre newsletter a bien été prise en compte !');

        return $this->redirectToRoute('app_home');
    }

}