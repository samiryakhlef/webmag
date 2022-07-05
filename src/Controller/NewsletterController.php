<?php

namespace App\Controller;


use App\Entity\Newsletter;
use App\Form\NewsletterType;
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
        NewsletterService $newsletterService,
    ): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //je récupère les données du formulaire
            $newsletter = $form->getData();
            //je récupère ma fonction persistContact pour envoyer en base de données
            $newsletterService-> persistNewsletter($newsletter);
            // je récupère ma fonction sendNewsletterEmail pour envoyer un email
            $newsletterService->sendNewsletterEmail($newsletter, $newsletter->getValidationToken());
            //je redirige ma vue vers la page de contact
            $this->addFlash('success', 'Votre inscription à notre newsletter à bien été pris en compte !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/newsletter/desinscription/{token}', name: 'app_newsletter_unsubscribe')]
    public function unsubscribe( NewsletterService $newsletterService, Newsletter $newsletter, $token): Response
    {
        //je récupère le service newsletterService
        $newsletterService ->unsubscribeNewsletter($token, $newsletter);
        
        return $this->render('newsletter/unsubscribe.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }

}