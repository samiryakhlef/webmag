<?php

namespace App\Tests;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\BlogPost;
use PHPUnit\Framework\TestCase;

class BlogPostUnitTest extends TestCase
{
    // test que la valeur est attendue est bonne
    public function testIsTrue(): void
    {
        $blogpost = new BlogPost();
        $datetime = new DateTimeImmutable();
        $user = new User();

        //test que la valeur est attendue est bonne
        $blogpost->setTitre('titre')
            ->setContenu('contenu')
            ->setCreatedAt($datetime)
            ->setSlug('slug')
            ->setAuteur('auteur')
            ->setUser($user);

        $this->assertTrue($blogpost->getTitre() === 'titre');
        $this->assertTrue($blogpost->getContenu() === 'contenu');
        $this->assertTrue($blogpost->getCreatedAt() === $datetime);
        $this->assertTrue($blogpost->getSlug() === 'slug');
        $this->assertTrue($blogpost->getUser() === $user);
        $this->assertTrue($blogpost->getAuteur() === 'auteur');
        $this->assertTrue($blogpost->getId() === null);
    }

    public function testIsFalse(): void
    {
        $blogpost = new BlogPost();
        $datetime = new DateTimeImmutable();
        $user = new User();

        $blogpost->setTitre('titre')
            ->setContenu('contenu')
            ->setCreatedAt($datetime)
            ->setSlug('slug')
            ->setAuteur('auteur')
            ->setUser($user);

        $this->assertFalse($blogpost->getTitre() === 'titre2');
        $this->assertFalse($blogpost->getContenu() === 'contenu2');
        $this->assertFalse($blogpost->getCreatedAt() === new DateTimeImmutable());
        $this->assertFalse($blogpost->getSlug() === 'slug2');
        $this->assertFalse($blogpost->getUser() === new User());
        $this->assertFalse($blogpost->getAuteur() === 'auteur2');
        $this->assertFalse($blogpost->getId() === 'false');
    }

    public function testIsEmpty(): void
    {
        $blogpost = new BlogPost();
        $this->assertEmpty($blogpost->getTitre());
        $this->assertEmpty($blogpost->getContenu());
        $this->assertEmpty($blogpost->getCreatedAt());
        $this->assertEmpty($blogpost->getSlug());
        $this->assertEmpty($blogpost->getUser());
        $this->assertEmpty($blogpost->getAuteur());
        $this->assertEmpty($blogpost->getId());
    }
}