<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class LinkedinAuthenticator extends OAuth2Authenticator
{

    public const LOGIN_ROUTE = 'app_login_linkedin';

    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;

    public function __construct(ClientRegistry $clientRegistry,
    EntityManagerInterface $entityManager,
    RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_linkedin_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('linkedin');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                /** @var LinkedinUser $linkedinUser */
                $linkedinUser = $client->fetchUserFromToken($accessToken);

                // have they logged in with linkedinbefore? Easy!
                $existingUser = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy([
                    'social' => $linkedinUser->getId()
                ]);

                if ($existingUser) {
                    return $existingUser;
                }

                // if the user has an a account with linkedin email, we can link it to the account
                $existingUser = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy([
                    'email' => $linkedinUser->getEmail()
                ]);

                if ($existingUser) {
                    $existingUser->setSocial($linkedinUser->getId());
                    $this->entityManager->flush();

                    return $existingUser;
                }

                //User doesnt exist, we create it !
                if (!$existingUser) {
                    $existingUser = new User();
                    $existingUser
                        ->setEmail($linkedinUser->getEmail())
                        ->setSocial($linkedinUser->getId())
                        ->setRoles(['ROLE_USER'])
                        ->setPassword('')
                        ->setPrenom('Prénom')
                        ->setNom('Nom')
                        ->setPseudo($linkedinUser->getName());;
                    $this->entityManager->persist($existingUser);
                }

                $this->entityManager->flush();

                return $existingUser;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, 
    TokenInterface $token, 
    string $firewallName): ?Response
    {
        return new RedirectResponse(
            $this->router->generate('app_home')
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}
