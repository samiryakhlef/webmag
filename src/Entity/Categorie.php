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

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $inMenu = false;
    
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getNom(): ?string
    {
        return $this->nom;
    }
    
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        
        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription(string $description): self
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    /**
    * @return Collection<int, Article>
    */
    public function getArticles(): Collection
    {
        return $this->articles;
    }
    
    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addCategorie($this);
        }
        
        return $this;
    }
    
    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategorie($this);
        }
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->nom;
    }

    public function isInMenu(): ?bool
    {
        return $this->inMenu;
    }

    public function setInMenu(?bool $inMenu): self
    {
        $this->inMenu = $inMenu;

        return $this;
    }
}
