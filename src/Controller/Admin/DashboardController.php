<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\BlogPost;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name:'admin')]
    function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
    
    function configureDashboard(): Dashboard
    {
        return Dashboard::new ()
        ->setTitle('Yadelair');
    }
    
    function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        //section de la gestions des articles
        yield MenuItem::section('Gestion des articles');
        yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Article::class);

        //section de la gestions des categories
        yield MenuItem::section('Gestion des catégories');
        yield MenuItem::linkToCrud('Categorie', 'fas fa-calendar-check', Categorie::class);

        //section de la gestions des utilisateurs
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);

        //section de la gestions des Blogposts
        yield MenuItem::section('Gestion des Blogpost');
        yield MenuItem::linkToCrud('Blogpost', 'fas fa-blogger', BlogPost::class);
    }
}
