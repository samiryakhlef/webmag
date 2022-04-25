<?php

namespace App\Tests;



use App\Entity\User;

use DateTimeImmutable;






use App\Entity\Article;
use App\Entity\Categorie;
use PHPUnit\Framework\TestCase;


// test des setter et getter de la classe Article
class ArticleUnitTest extends TestCase
{
    // test que la valeur est attendue est bonne
    public function testIsTrue(): void
    {
        $article = new Article();
        $datetime = new DateTimeImmutable();
        $categorie = new Categorie();
        $user = new User();

        $article->setTitre('titre')
            ->setContenu('contenu')
            ->setCreatedAt($datetime)
            ->setNotification(true)
            ->setSlug('slug')
            ->setFile('file')
            ->setUser($user)
            ->addCategorie($categorie);

        $this->assertTrue($article->getTitre() === 'titre');
        $this->assertTrue($article->getContenu() === 'contenu');
        $this->assertTrue($article->getCreatedAt() === $datetime);
        $this->assertTrue($article->getNotification() === true);
        $this->assertTrue($article->getSlug() === 'slug');
        $this->assertTrue($article->getFile() === 'file');
        $this->assertTrue($article->getUser() === $user);
        $this->assertContains($categorie, $article->getCategorie());
    }

    // test que la valeur est attendue est fausse
    public function testIsFalse(): void
    {
        $article = new Article();
        $datetime = new DateTimeImmutable();
        $categorie = new Categorie();
        $user = new User();
        
        $article->setTitre('titre')
            ->setContenu('contenu')
            ->setCreatedAt($datetime)
            ->setNotification(true)
            ->setSlug('slug')
            ->setFile('file')
            ->setUser($user)
            ->addCategorie($categorie);

        $this->assertFalse($article->getTitre() === 'false');
        $this->assertFalse($article->getContenu() === 'false');
        $this->assertFalse($article->getCreatedAt() === new DateTimeImmutable());
        $this->assertFalse($article->getNotification() === false);
        $this->assertFalse($article->getSlug() === 'false');
        $this->assertFalse($article->getFile() === 'false');
        $this->assertFalse($article->getUser() === new User());
        $this->assertNotContains(new Categorie(), $article->getCategorie());
    }

    // test que la valeur est attendue est null
    public function testIsEmpty(): void
    {
        $article = new Article();
        $this->assertEmpty($article->getTitre());
        $this->assertEmpty($article->getContenu());
        $this->assertEmpty($article->getCreatedAt());
        $this->assertEmpty($article->getNotification());
        $this->assertEmpty($article->getSlug());
        $this->assertEmpty($article->getFile());
        $this->assertEmpty($article->getUser());
        $this->assertEmpty($article->getCategorie());
    }
}
