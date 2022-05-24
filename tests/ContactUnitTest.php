<?php

namespace App\Tests;

use DateTimeImmutable;
use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $contact = new Contact();
        $datetime = new DateTimeImmutable();
        $contact->setNom('nom')
            ->setEmail('true@test.com')
            ->setMessage('message')
            ->setCreatedAt($datetime)
            ->setIsSend(true);

        $this->assertTrue($contact->getNom() === 'nom');
        $this->assertTrue($contact->getEmail() === 'true@test.com');
        $this->assertTrue($contact->getMessage() === 'message');
        $this->assertTrue($contact->getCreatedAt() === $datetime);
        $this->assertTrue($contact->getId() === null);
        $this->assertTrue($contact->getIsSend() === true);
    }

    public function testIsFalse(): void
    {
        $contact = new Contact();
        $datetime = new DateTimeImmutable();
        $contact->setNom('nom')
            ->setEmail('true@test.com')
            ->setMessage('message')
            ->setCreatedAt($datetime)
            ->setIsSend(false);


        $this->assertFalse($contact->getNom() === 'false');
        $this->assertFalse($contact->getEmail() === 'false@test.com');
        $this->assertFalse($contact->getMessage() === 'false');
        $this->assertFalse($contact->getCreatedAt() === new dateTimeImmutable());
        $this->assertFalse($contact->getId() === 'false');
        $this->assertFalse($contact->getIsSend() === 'false');
    }

    public function testIsEmpty(): void
    {
        $contact = new Contact();
        $this->assertEmpty($contact->getNom());
        $this->assertEmpty($contact->getEmail());
        $this->assertEmpty($contact->getMessage());
        $this->assertEmpty($contact->getCreatedAt());
        $this->assertEmpty($contact->getId());
        $this->assertEmpty($contact->getIsSend());
    }
}
