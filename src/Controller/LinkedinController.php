<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LinkedinController extends AbstractController
{
    #[Route('/connexion/linkedin', name: 'app_login_linkedin')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        //Redirect to linkedin for authentication
        return $clientRegistry->getClient('linkedin')->redirect
            ([
                'r_liteprofile',
                'r_emailaddress'
            ],[]);
    }

    /**
     * After going to linkedin, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     */
    #[Route('/connexion/linkedin/check', name: 'connect_linkedin_check')]
    public function connectCheckAction(Request $request)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
    }

}
