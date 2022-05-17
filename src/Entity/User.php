<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass:UserRepository::class)]
#[ORM\Table(name:'`user`')]
#[UniqueEntity(fields:['email'], message:'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type:'integer')]
private $id;

#[ORM\Column(type:'string', length:180, unique:true)]
private $email;

#[ORM\Column(type:'json')]
private $roles = [];

#[ORM\Column(type:'string')]
private $password;

#[ORM\Column(type:'string', length:255)]
private $prenom;

#[ORM\Column(type:'string', length:255)]
private $nom;

#[ORM\Column(type:'string', length:255)]
private $pseudo;

#[ORM\Column(type:'integer', options:['default' => 0])]
private $contribution;

#[ORM\Column(type:'text', nullable:true)]
private $aPropos;

#[ORM\Column(type:'string', length:255)]
private $social;

#[ORM\OneToMany(mappedBy:'user', targetEntity:Article::class, orphanRemoval:true)]
private $article;

#[ORM\OneToMany(mappedBy:'user', targetEntity:BlogPost::class, orphanRemoval:true)]
private $blogPosts;

public function __construct()
    {
    $this->article = new ArrayCollection();
    $this->blogPosts = new ArrayCollection();
}

function getId(): ?int
    {
    return $this->id;
}

function getEmail(): ?string
    {
    return $this->email;
}

function setEmail(string $email): self
    {
    $this->email = $email;

    return $this;
}

/**
 * A visual identifier that represents this user.
 *
 * @see UserInterface
 */
function getUserIdentifier(): string
    {
    return (string) $this->email;
}

/**
 * @see UserInterface
 */
function getRoles(): array
{
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
}

function setRoles(array $roles): self
    {
    $this->roles = $roles;

    return $this;
}

/**
 * @see PasswordAuthenticatedUserInterface
 */
function getPassword(): string
    {
    return $this->password;
}

function setPassword(string $password): self
    {
    $this->password = $password;

    return $this;
}

/**
 * @see UserInterface
 */
function eraseCredentials()
    {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
}

function getPrenom(): ?string
    {
    return $this->prenom;
}

function setPrenom(string $prenom): self
    {
    $this->prenom = $prenom;

    return $this;
}

function getNom(): ?string
    {
    return $this->nom;
}

function setNom(string $nom): self
    {
    $this->nom = $nom;

    return $this;
}

function getPseudo(): ?string
    {
    return $this->pseudo;
}

function setPseudo(string $pseudo): self
    {
    $this->pseudo = $pseudo;

    return $this;
}
function getContribution(): ?int
    {
    return $this->contribution;
}

function setContribution(int $contribution): self
    {
    $this->contribution = $contribution;

    return $this;
}

function getAPropos(): ?string
    {
    return $this->aPropos;
}

function setAPropos(?string $aPropos): self
    {
    $this->aPropos = $aPropos;

    return $this;
}

function getSocial(): ?string
    {
    return $this->social;
}

function setSocial(string $social): self
    {
    $this->social = $social;

    return $this;
}

/**
 * @return Collection<int, Article>
 */
function getArticle(): Collection
    {
    return $this->article;
}

function addArticle(Article $article): self
    {
    if (!$this->article->contains($article)) {
        $this->article[] = $article;
        $article->setUser($this);
    }

    return $this;
}

function removeArticle(Article $article): self
    {
    if ($this->article->removeElement($article)) {
        // set the owning side to null (unless already changed)
        if ($article->getUser() === $this) {
            $article->setUser(null);
        }
    }

    return $this;
}

/**
 * @return Collection<int, BlogPost>
 */
function getBlogPosts(): Collection
    {
    return $this->blogPosts;
}

function addBlogPost(BlogPost $blogPost): self
    {
    if (!$this->blogPosts->contains($blogPost)) {
        $this->blogPosts[] = $blogPost;
        $blogPost->setUser($this);
    }

    return $this;
}

function removeBlogPost(BlogPost $blogPost): self
    {
    if ($this->blogPosts->removeElement($blogPost)) {
        // set the owning side to null (unless already changed)
        if ($blogPost->getUser() === $this) {
            $blogPost->setUser(null);
        }
    }

    return $this;
}
}
