<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    //je crée une route pour la catégorie initiative
    #[Route('/article/{slug}', name: 'app_article_index')]
    public function categorie(Article $article): Response
        {
            //je retourne ma vue avec les articles de la catégorie correspondante
            return $this->render('article/index.html.twig', compact('article'));
        }

}
