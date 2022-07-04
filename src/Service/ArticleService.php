<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\Mime\Email;
use App\Repository\ArticleRepository;

final class ArticleService
{
    public function __construct(
        private ArticleRepository $articleRepository
        ) {}
    /**
     * Retrieve article in menu
     */
    public function lastArticle()
    {
        return $this->articleRepository->findOneBy(['published' => true], ['id' => 'DESC']);

    }
    public function sendEmailArticle(Article $article)
    {
        $email = (new Email())
            ->from('yriche@labconseil.fr')
            ->to($article->getUser()->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Yadelair : Vous avez un nouvel article')
            ->text('Félicitation Votre article '.$article->getTitre().' a bien été publié');

            $this->mailer->send($email);
    }

}