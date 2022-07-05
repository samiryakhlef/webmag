<?php

namespace App\Controller;


use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Newsletter;
use App\Entity\Subscriber;
use App\Form\NewsletterType;
use App\Form\SubscriberType;
use App\Repository\UserRepository;
use App\Service\NewsletterService;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        //je stock ArticleRepository dans une variable
        ArticleRepository $articleRepository,Request $request, NewsletterService $newsletterService,UserRepository $userRepository): Response 
        {
            // $newsletter = new Newsletter();
            // $form = $this->createForm(NewsletterType::class, $newsletter,[
            //     'action' => $this->generateUrl('app_newsletter'),
            //     'method' => 'POST',
            // ]);

            $subscriber = new Subscriber();
            // $form = $this->createForm(NewsletterType::class, $newsletter);
            $form = $this->createForm(SubscriberType::class, $subscriber, [
                'action' => $this->generateUrl('app_newsletter'),
                'method' => 'POST',
            ]);
            $form->handleRequest($request);

            return $this->render('home/index.html.twig', [
                //je récupère les 4 derniers articles de manière decroisssantes
                'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),
                'form' => $form->createView(),
                'users' => $userRepository->profil(),
                'user' => $this->getUser(),
            ]);
        }

    #[Route('/newsletter', name: 'app_newsletter')]
    public function newsletter(Request $request,NewsletterService $newsletterService,): Response 
        {
            $newsletter = new Newsletter();
            $form = $this->createForm(NewsletterType::class, $newsletter);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $newsletter = $form->getData();
                //je récupère ma fonction persistContact pour envoyer en base de données
                $newsletterService->persistNewsletter($newsletter);
                // je récupère ma fonction sendNewsletterEmail pour envoyer un email
                $newsletterService->sendNewsletterEmail($newsletter);
                //je j'envoie un message de confirmation d'inscritption à la newsletter
                $this->addFlash('success', 'Votre inscription à notre newsletter à bien été pris en compte !');
                //je redirige ma vue vers la page de contact
                return $this->redirectToRoute('app_home');
            }

            return $this->render('newsletter/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }
}
