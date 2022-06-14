<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    //je créer une route pour la catégorie initiative
    #[Route('/article/{slug}', name: 'app_article_index')]

    public function categorie(
        Article $article,
    ): Response {
        //je retourne ma vue avec les articles de la catégorie correspondante
        return $this->render('article/index.html.twig', [
            //je récupère les catégories de la catégorie voulu en locurence initiative ainsi que les articles de cette catégorie
            'article' => $article,
        ]);
    }
}
