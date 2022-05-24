<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

// test des setter et getter de la classe User

class UserUnitTest extends TestCase
{

    // test que la valeur est attendue est bonne
    public function testsTrue()
    {
        $user = new User();
        $user->setEmail('true@test.com')
            ->setPrenom('prenom')
            ->setNom('nom')
            ->setPseudo('pseudo')
            ->setSocial('social')
            ->setAPropos('a propos')
            ->setRoles(['ROLE_USER'])
            ->setContribution(0);

        $this->assertTrue($user->getEmail() === 'true@test.com');
        $this->assertTrue($user->getPrenom() === 'prenom');
        $this->assertTrue($user->getNom() === 'nom');
        $this->assertTrue($user->getPseudo() === 'pseudo');
        $this->assertTrue($user->getSocial() === 'social');
        $this->assertTrue($user->getAPropos() === 'a propos');
        $this->assertTrue($user->getRoles() === ['ROLE_USER']);
        $this->assertTrue($user->getContribution() === 0);
        $this->assertTrue($user->getId() === null);
    }

    // test que la valeur est attendue est fausse
    public function testsFalse()
    {
        $user = new User();
        $user->setEmail('true@test.com')
            ->setPrenom('prenom')
            ->setNom('nom')
            ->setPseudo('pseudo')
            ->setSocial('social')
            ->setAPropos('a propos')
            ->setRoles(['ROLE_USER'])
            ->setContribution(0);

        $this->assertFalse($user->getEmail() === 'false@test.com');
        $this->assertFalse($user->getPrenom() === 'false');
        $this->assertFalse($user->getNom() === 'false');
        $this->assertFalse($user->getPseudo() === 'false');
        $this->assertFalse($user->getSocial() === 'false');
        $this->assertFalse($user->getAPropos() === 'false');
        $this->assertFalse($user->getRoles() === ['false']);
        $this->assertFalse($user->getContribution() === 1);
        $this->assertFalse($user->getId() === 'false');
    }

    // test que la valeur est attendue est null
    public function testsEmpty()
    {
        $user = new User();
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPrenom());
        $this->assertEmpty($user->getNom());
        $this->assertEmpty($user->getPseudo());
        $this->assertEmpty($user->getSocial());
        $this->assertEmpty($user->getAPropos());
        $this->assertEmpty($user->getId());
        $this->assertEmpty($user->getContribution());
    }
}
