<?php

namespace App\Service;

use App\Repository\ArticleRepository;

final class ArticleService
{
    public function __construct(private ArticleRepository $articleRepository){}
    /**
     * Retrieve article in menu
     */
    public function lastArticle()
    {
        return $this->articleRepository->findOneBy(['published' => true], ['id' => 'DESC']);

    }
    
}