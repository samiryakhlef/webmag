<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        //je stock ArticleRepository dans une variable
        ArticleRepository $articleRepository,
    ): Response {
        return $this->render('home/index.html.twig', [
            //je récupère les 4 derniers articles de manière decroisssantes
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),
        ]);
    }

    #[Route('details/{slug}', name: 'app_article_details')]
    public function details(Article $article): Response
    {
        return $this->render('home/details.html.twig', [
            //je récupère les informations de l'article
            'article' => $article,
            //je récupère les 4 derniers articles de la navbar
            'articles' => $article,
        ]);
    }
}
