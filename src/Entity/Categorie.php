<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type:'integer')]
private $id;

#[ORM\Column(type:'string', length:255)]
private $nom;

#[ORM\Column(type:'text')]
private $description;

#[ORM\Column(type:'string', length:255)]
private $slug;

#[ORM\ManyToMany(targetEntity:Article::class, mappedBy:'categorie')]
private $articles;

public function __construct()
    {
    $this->articles = new ArrayCollection();
}

function getId(): ?int
    {
    return $this->id;
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

 function getDescription(): ?string
    {
    return $this->description;
}

function setDescription(string $description): self
    {
    $this->description = $description;

    return $this;
}

function getSlug(): ?string
    {
    return $this->slug;
}

function setSlug(string $slug): self
    {
    $this->slug = $slug;

    return $this;
}

/**
 * @return Collection<int, Article>
 */
function getArticles(): Collection
    {
    return $this->articles;
}

function addArticle(Article $article): self
    {
    if (!$this->articles->contains($article)) {
        $this->articles[] = $article;
        $article->addCategorie($this);
    }

    return $this;
}

function removeArticle(Article $article): self
    {
    if ($this->articles->removeElement($article)) {
        $article->removeCategorie($this);
    }

    return $this;
}

}
