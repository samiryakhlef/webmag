<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\BlogPost;
use App\Entity\Categorie;
use App\Entity\Newsletter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
 * @IsGranted("ROLE_USER")
 */
    #[Route('/admin', name:'admin')]
    function index(): Response
        {
            return $this->render('admin/dashboard.html.twig');
        }
    // configuration du menu 
    public function configureUserMenu(UserInterface $user): UserMenu
        {
            // Usually it's better to call the parent method because that gives you a
            // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
            // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
            return parent::configureUserMenu($user)
                // use the given $user object to get the user name
                ->setName($user->getUserIdentifier())
                // use this method if you don't want to display the name of the user
                ->displayUserName(true)

                // you can use any type of menu item, except submenus
                ->addMenuItems([
                    MenuItem::linkToRoute('Profil', 'fa fa-user', 'app_user_profil'),
                    MenuItem::linkToRoute('Accueil', 'fa fa-home', 'app_home'),
                ]);
        }

    
    function configureDashboard(): Dashboard
        {
            return Dashboard::new ()
            ->setTitle('Yadelair');
        }
    
    function configureMenuItems(): iterable
        {
            
                if($this->isGranted('ROLE_ADMIN'))
                {
                        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
                    //section de la gestions des utilisateurs
                    yield MenuItem::section('Utilisateurs');
                    yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);

                    //section de la gestions des categories
                    yield MenuItem::section('Gestion des catégories');
                    yield MenuItem::linkToCrud('Categorie', 'fa fa-tags', Categorie::class);

                    //demande de Contact
                    yield MenuItem::section('Demandes de contact');
                    yield MenuItem::linkToCrud('Demandes de contact', 'fas fa-envelope', Contact::class);

                    //section de la gestions des Blogposts
                    yield MenuItem::section('Gestion des Blogpost');
                    yield MenuItem::linkToCrud('Blogpost', 'fas fa-blogger', BlogPost::class);

                    //section de la newsletter
                    yield MenuItem::section('Newsletter');
                    yield MenuItem::linkToCrud('Newsletter', 'fas fa-newspaper', Newsletter::class);
                }
                if ($this->isGranted('ROLE_USER'))
                {
                    //section de la gestions des articles
                    yield MenuItem::section('Gestion des articles');
                    yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper',Article::class);
                
                }
        }

}

