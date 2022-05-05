<?php

namespace App\Controller\Navigation;

use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DossierController extends AbstractController
{
    #[Route('/dossier', name: 'app_dossier')]
    public function index(ArticleRepository $articleRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('dossier/index.html.twig', [
            'controller_name' => 'DossierController',
            //je récupère les 4 derniers articles de manière decroisssantes
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),
            //je récupère la catégorie coresspondante
            'categories' => $categorieRepository->findBy(['nom' => 'dossier']),
        ]);
    }
    #[Route('/dossier/{slug}', name: 'app_dossier_categorie')]
    public function categorie(Categorie $categorie, ArticleRepository $articleRepository): Response
    {
        //je récupère les articles de la catégorie
        $articles = $articleRepository->findAllArticle($categorie);
        //je récupère les catégories de la catégorie
        return $this->render('dossier/categorie.html.twig', [
            'categorie' => $categorie,
            'articles' => $articles,
        ]);
    }
}
