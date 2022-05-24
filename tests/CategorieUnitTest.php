<?php

namespace App\Tests;

use App\Entity\Categorie;
use PHPUnit\Framework\TestCase;

// test des setter et getter de la classe Categorie
class CategorieUnitTest extends TestCase
{
    // test que la valeur est attendue est bonne
    public function testIsTrue(): void
    {
        $categorie = new Categorie();
        $categorie->setNom('nom')
            ->setDescription('description')
            ->setSlug('slug')
            ->setInMenu(true);

        $this->assertTrue($categorie->getNom() === 'nom');
        $this->assertTrue($categorie->getDescription() === 'description');
        $this->assertTrue($categorie->getSlug() === 'slug');
        $this->assertTrue($categorie->IsInMenu() === true);
        $this->asserttrue($categorie->getId() === null);
    }

    // test que la valeur est attendue est fausse
    public function testIsFalse(): void
    {
        $categorie = new Categorie();
        $categorie->setNom('nom')
            ->setDescription('description')
            ->setSlug('slug')
            ->setInMenu(false);

        $this->assertFalse($categorie->getNom() === 'false');
        $this->assertFalse($categorie->getDescription() === 'false');
        $this->assertFalse($categorie->getSlug() === 'false');
        $this->assertFalse($categorie->IsInMenu() === 'false');
        $this->assertFalse($categorie->getId() === 'false');
    }
    // test que la valeur est attendue est null
    public function testIsEmpty(): void
    {
        $categorie = new Categorie();
        $this->assertEmpty($categorie->getNom());
        $this->assertEmpty($categorie->getDescription());
        $this->assertEmpty($categorie->getSlug());
        $this->assertEmpty($categorie->getId());
        $this->assertEmpty($categorie->getArticles());
        $this->assertEmpty($categorie->IsInMenu());
    }
}
