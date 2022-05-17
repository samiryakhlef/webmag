<?php

namespace App\Service;

use App\Repository\CategorieRepository;

final class CategorieService
{
    public function __construct(private CategorieRepository $categorieRepository){}
    /**
     * Retrieve categorie in menu
     */
    public function getInMenuCategories(): array
    {
        $inMenuCategorie = $this->categorieRepository->findBy(['inMenu' => true]);

        return $inMenuCategorie;
    }
    
    /**
     * Retrieve categorie not in menu
     */
    public function getNotInMenuCategories(): array
    {
        $inMenuCategorie = $this->categorieRepository->findBy(['inMenu' => false]);

        return $inMenuCategorie;
    }
}