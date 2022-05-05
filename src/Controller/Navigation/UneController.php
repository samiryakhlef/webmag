<?php

namespace App\Controller\Navigation;

use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UneController extends AbstractController
{
    #[Route('/une', name: 'app_une')]
    public function index(ArticleRepository $articleRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('une/index.html.twig', [
            'controller_name' => 'UneController',
            //je récupère les 4 derniers articles de manière decroisssantes
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),
            //je récupère la catégorie coresspondante
            'categories' => $categorieRepository->findBy(['nom' => 'a la une']),
        ]);
    }
    #[Route('/a-la-une/{slug}', name: 'app_a-la-une_categorie')]
    public function categorie(Categorie $categorie, ArticleRepository $articleRepository): Response
    {
        //je récupère les articles de la catégorie
        $articles = $articleRepository->findAllArticle($categorie);
        //je récupère les catégories de la catégorie
        return $this->render('une/categorie.html.twig', [
            'categorie' => $categorie,
            'articles' => $articles,
        ]);
    }
}
