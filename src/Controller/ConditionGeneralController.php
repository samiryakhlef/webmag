<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionGeneralController extends AbstractController
{
    #[Route('/condition/general', name: 'app_condition_general')]
    public function index(): Response
    {
        return $this->render('condition_general/index.html.twig', [
            'controller_name' => 'ConditionGeneralController',
        ]);
    }

    #[Route('/politique/de/confidentialité', name: 'app_privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('condition_general/privacyPolicy.html.twig', [
            'controller_name' => 'ConditionGeneralController',
        ]);
    }

    #[Route('/charte/du/site', name: 'app_charte_site')]
    public function charte(): Response
    {
        return $this->render('condition_general/websiteCharter.html.twig', [
            'controller_name' => 'ConditionGeneralController',
        ]);
    }
}
