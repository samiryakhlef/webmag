<?php

namespace App\Controller\Navigation;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'app_category_index')]
    public function index(
        //j'instancie mes categorie et je les stocks dans $categorie
        Categorie $category,

        //j'instancie mes articles et je les stocks dans $articleRepository 
        ArticleRepository $articleRepository,

        //j'instancie mon paginateur et je le stocks dans $paginator    (PaginatorInterface)
        PaginatorInterface $paginator,

        //j'instancie ma requête et je la stocks dans $request
        Request $request

    ): Response 
    {
        //je stock les articles dans la variable $data
        $data = $articleRepository->last($this->getParameter('app.max_articles') ?? 6, $category);

        //je pagine les articles
        $paginations = $paginator->paginate(

            //j'instancie la variable $data
            $data,

            //je recuère la request en paramètre et la query je nom le paramètre 'page'visible dans l'url et je lui donne la valeur 1 pour la page par defaut
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('category/index.html.twig', [

            //je récupère les 4 derniers articles de manière decroisssantes
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4, $category),

            //je récupère la catégorie coresspondante
            'categorie' => $category,

            //je récupère les articles paginés
            'paginations' => $paginations
        ]);
    }
}
