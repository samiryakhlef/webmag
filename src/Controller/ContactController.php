<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Service\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, ArticleRepository $articleRepository, ContactService $contactService): Response
    {
        //je créer un nouveau contact
        $contact = new Contact();

        //je créé un formulaire pour ce contact
        $form = $this->createForm(ContactType::class, $contact);

        //je vérifie si le formulaire 
        $form->handleRequest($request);

        //si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {

            //je récupère le contact
            $contact = $form->getData();

            //je récupère ma fonction persistContact pour envoyer en base de données
            $contactService->persistContact($contact);

            //je j'envoie un message de confirmation
            $this->addFlash('success', 'Votre message a bien été envoyé');

            //je redirige ma vue vers la page de contact
            return $this->redirectToRoute('app_contact');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'articles' => $articleRepository->last($this->getParameter('app.max_articles') ?? 4),
        ]);
    }
}
