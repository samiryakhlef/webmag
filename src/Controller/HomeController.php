<?php

namespace App\Controller;


use DateTimeImmutable;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Service\NewsletterService;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        //je stock ArticleRepository dans une variable
        ArticleRepository $articleRepository,
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
            $newsletterService->persistNewsletter($newsletter);
            // je récupère ma fonction sendNewsletterEmail pour envoyer un email
            $newsletterService->sendNewsletterEmail();
            //je j'envoie un message de confirmation d'inscritption à la newsletter
            $this->addFlash('success', 'Votre inscription à notre newsletter à bien été pris en compte !');
            //je redirige ma vue vers la page de contact
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            //je récupère les 4 derniers articles de manière decroisssantes
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),
            'form' => $form->createView(),
        ]);
        }

        //constructeur pour les cookies
    public function __construct()
        {
            $cookie = Cookie::create('cookie')
            ->withValue('cookie')
            ->withExpires(new \DateTimeImmutable('+1 year'))
            ->withDomain('https://127.0.0.1/')
            ->withPath('/')
            ->withSecure(true)
            ->withHttpOnly(true);
        }  

        //envoyer des cookies 
    public function sendCookie(): Response
        {
            $response = new Response();
            $cookie = Cookie::create('cookie')
            ->withValue('cookie')
            ->withExpires(new \DateTimeImmutable('+1 year'))
            ->withDomain('localstorage')
            ->withPath('/')
            ->withSecure(true)
            ->withHttpOnly(true);
            $cookie = $response->headers->setCookie($cookie);
            if(!isset($_COOKIE['cookie'])){
                $response->send();
            }
            return $response;
            $content = $this->renderView('base/footer.html.twig', [
                'cookies' => $cookie,
            ]);   
        }
}
