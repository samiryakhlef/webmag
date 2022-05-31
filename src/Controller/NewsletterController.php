<?php

namespace App\Controller;


use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Service\ContactService;
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
            $newsletter = $form->getData();
            //je récupère ma fonction persistContact pour envoyer en base de données
            $newsletterService-> persistNewsletter($newsletter);
            // je récupère ma fonction sendNewsletterEmail pour envoyer un email
            $newsletterService->sendNewsletterEmail();
            //je j'envoie un message de confirmation d'inscritption à la newsletter
            $this->addFlash('success', 'Votre inscription à notre newsletter à bien été pris en compte !');
            //je redirige ma vue vers la page de contact
            return $this->redirectToRoute('app_home');
        }

        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
            'form' => $form->createView(),
        ]);
    }
}