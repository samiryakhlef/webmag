<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name:'app_profil')]
function index(
    //j'instancie mes articles et je les stocks dans $articleRepository
    ArticleRepository $articleRepository,

    //j'instancie mes utilisateurs et je les stocks dans $userRepository
    UserRepository $userRepository,

    //j'instancie mon paginateur et je le stocks dans $paginator    (PaginatorInterface)
    PaginatorInterface $paginator,

    //j'instancie ma requête et je la stocks dans $request
    Request $request

): Response {

    //je stock les articles dans la variable $data
    $data = $articleRepository->last($this->getParameter('app.max_articles') ?? 4);

    //je pagine les articles
    $paginations = $paginator->paginate(

        //j'instancie la variable $data
        $data,

        //je recuère la request en paramètre et la query je nom le paramètre 'page'visible dans l'url et je lui donne la valeur 1 pour la page par defaut
        $request->query->getInt('page', 1),
        1
    );

    return $this->render('profil/index.html.twig', [

        //je récupère les 4 derniers articles de manière decroisssantes
        'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),

        //je récupère les informations du profil
        'profils' => $userRepository->profil(),

        //je récupère les articles paginés
        'paginations' => $paginations,
    ]);
}
}
