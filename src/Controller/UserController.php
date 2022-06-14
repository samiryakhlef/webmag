<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_user_profil')]
    function index(
        //j'instancie mes articles et je les stocks dans $articleRepository
        ArticleRepository $articleRepository,

        //j'instancie mes utilisateurs et je les stocks dans $userRepository
        UserRepository $userRepository,

        //j'instancie mon paginateur et je le stocks dans $paginator    (PaginatorInterface)
        PaginatorInterface $paginator,

        //j'instancie ma requête et je la stocks dans $request
        Request $request,

    ): Response {

        //je stock les articles dans la variable $data
        $data = $articleRepository->last($this->getParameter('app.max_articles') ?? 3);

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
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 3),

            //je récupère les informations du profil
            'users' => $userRepository->profil(),

            //je récupère les articles paginés
            'paginations' => $paginations,
        ]);
    }


    // formulaire d'edition du profil de l'utilisateur connecté
    #[Route('/profil/{id}/edit', name: 'app_profil_show', methods: ['GET', 'POST'])]
    public function edit(
        //J'INSTANCICE MA CLASSE USER
        User $user,
        //J'INSTANCIE LA CLASS REQUEST
        Request $request,
        //J'INSTANCIE MON ENTITYMANAGER
        EntityManagerInterface $em,
    ): Response {
        // je crée un formulaire pour l'utilisateur Connecté
        $form = $this->createForm(UserFormType::class, $this->getUser());
        $form->handleRequest($request);
        //JE VÉRIFIE SI LE FORMULAIRE ET BIEN SOUMIS ET VALIDE 
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());

            //JE PERSISTE LES MODIFICATIONS
            $em->persist($user);
            //JE FLUSH EN BASES DE DONNÉES 
            $em->flush();
            // JE REDIRIGE L'UTILISATEUR VERS LA PAGE PROFIL
            return $this->redirectToRoute('app_user_profil');
        }
        //LORSQUE JE VEUS MODIFIER MON PROFIL JE REDIRIGE VERS LA VUE PROFIL/EDIT
        return $this->render('profil/edit.html.twig', [
            //JE CRÉE UN FORMULAIRE POUR L'UTILISATEUR CONNECTÉ
            'form' => $form->createView(),
        ]);
    }
}
