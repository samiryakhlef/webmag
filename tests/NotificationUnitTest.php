<?php

namespace App\Tests;

use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\BlogPost;
use App\Entity\Notification;
use PHPUnit\Framework\TestCase;

// test des setter et getter de la class Notification
class NotificationUnitTest extends TestCase
{
    // test que la valeur est attendue est true
    public function testIsTrue(): void
    {
        $notification = new Notification();
        $datetime = new DateTimeImmutable();
        $blogpost = new BlogPost();
        $article = new Article();

        $notification->setAuteur('auteur')
            ->setEmail('email')
            ->setContenu('contenu')
            ->setCreatedAt($datetime)
            ->setArticle($article)
            ->setBlogpost($blogpost);

        $this->assertTrue($notification->getAuteur() === 'auteur');
        $this->assertTrue($notification->getEmail() === 'email');
        $this->assertTrue($notification->getContenu() === 'contenu');
        $this->assertTrue($notification->getCreatedAt() === $datetime);
        $this->assertTrue($notification->getArticle() === $article);
    }

    // test que la valeur est attendue est false
    public function testIsFalse()
    {
        $notification = new Notification();
        $datetime = new DateTimeImmutable();
        $blogpost = new BlogPost();
        $article = new Article();

        $notification->setAuteur('auteur')
            ->setEmail('email')
            ->setContenu('contenu')
            ->setCreatedAt($datetime)
            ->setArticle($article)
            ->setBlogpost($blogpost);

        $this->assertFalse($notification->getAuteur() === 'false');
        $this->assertFalse($notification->getEmail() === 'false');
        $this->assertFalse($notification->getContenu() === 'false');
        $this->assertFalse($notification->getCreatedAt() ===  new DateTimeImmutable());
        $this->assertFalse($notification->getArticle() === 'false');
    }

    // test que la valeur est attendue est empty
    public function testIsEmpty()
    {
        $notification = new Notification();

        $this->assertEmpty($notification->getAuteur());
        $this->assertEmpty($notification->getEmail());
        $this->assertEmpty($notification->getContenu());
        $this->assertEmpty($notification->getCreatedAt());
        $this->assertEmpty($notification->getArticle());
    }
}
