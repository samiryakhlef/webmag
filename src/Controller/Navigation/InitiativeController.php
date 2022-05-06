<?php

namespace App\Controller\Navigation;

use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InitiativeController extends AbstractController
{
    #[Route('/initiative', name: 'app_initiative')]
    public function index(
        //j'instancie mes articles et je les stocks dans $articleRepository
        ArticleRepository $articleRepository,

        //j'instancie mes catégories et je les stocks dans $categorieRepository 
        CategorieRepository $categorieRepository,

        //j'instancie mon paginateur et je le stocks dans $paginator    (PaginatorInterface)
        PaginatorInterface $paginator,

        //j'instancie ma requête et je la stocks dans $request
        Request $request

    ): Response {
        //je stock les articles dans la variable $data
        $data = $articleRepository->last($this->getParameter('app.max_articles'), 6);

        //je pagine les articles
        $paginations = $paginator->paginate(

            //j'instancie la variable $data
            $data,

            //je recuère la request en paramètre et la query je nom le paramètre 'page'visible dans l'url et je lui donne la valeur 1 pour la page par defaut
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('initiative/index.html.twig', [

            //je récupère les 4 derniers articles de manière decroisssantes
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),

            //je récupère la catégorie coresspondante
            'categories' => $categorieRepository->findBy(['nom' => 'initiative']),

            //je récupère les articles paginés
            'paginations' => $paginations
        ]);
    }
    //je créer une route pour la catégorie initiative
    #[Route('/initiative/{slug}', name: 'app_initiative_categorie')]

    public function categorie(
        //j'instancie ma categorie et je la stocks dans $categorie
        Categorie $categorie,

        //j'instancie mes articles et je les stocks dans $articleRepository
        ArticleRepository $articleRepository
    ): Response {

        //je récupère les articles de la catégorie
        $articles = $articleRepository->findAllArticle($categorie);

        //je retourne ma vue avec les articles de la catégorie correspondante
        return $this->render('initiative/categorie.html.twig', [

            //je récupère les catégories de la catégorie voulu en locurence initiative ainsi que les articles de cette catégorie
            'categorie' => $categorie,
            'articles' => $articles,
        ]);
    }
}
