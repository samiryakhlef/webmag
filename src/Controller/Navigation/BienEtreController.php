<?php

namespace App\Controller\Navigation;

use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BienEtreController extends AbstractController
{
    #[Route('/bien/etre', name: 'app_bien_etre')]
    public function index(ArticleRepository $articleRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('bien_etre/index.html.twig', [
            'controller_name' => 'BienEtreController',
            //je récupère les 4 derniers articles de manière decroisssantes
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),
            //je récupère la catégorie coresspondante
            'categories' => $categorieRepository->findBy(['nom' => 'bien-etre']),
        ]);
    }
    #[Route('/bien-etre/{slug}', name: 'app_bien-etre_categorie')]
    public function categorie(Categorie $categorie, ArticleRepository $articleRepository): Response
    {
        //je récupère les articles de la catégorie
        $articles = $articleRepository->findAllArticle($categorie);
        //je récupère les catégories de la catégorie
        return $this->render('bien_etre/categorie.html.twig', [
            'categorie' => $categorie,
            'articles' => $articles,
        ]);
    }
}
