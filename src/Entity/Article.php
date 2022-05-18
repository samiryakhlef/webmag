<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'text')]
    private $contenu;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $auteur;

    #[ORM\Column(type: 'boolean')]
    private $notification;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $file;
    //mise en place du bundle vichuploader
    /**
     * @Vich\UploadableField(mapping="articles_images", fileNameProperty="file")
     * @var File
     */
    private $imageFile;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'article')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'articles')]
    private $categorie;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
    }

    function getId(): ?int
    {
        return $this->id;
    }

    function getTitre(): ?string
    {
        return $this->titre;
    }

    function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    function getContenu(): ?string
    {
        return $this->contenu;
    }

    function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    function getAuteur(): ?string
    {
        return $this->auteur;
    }

    function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    function getNotification(): ?bool
    {
        return $this->notification;
    }

    function setNotification(bool $notification): self
    {
        $this->notification = $notification;

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

    function getFile(): ?string
    {
        return $this->file;
    }

    function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }
    //getter et setter de vichuploader
    function setImageFile(File $file = null)
    {
        $this->imageFile = $file;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($file) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    function getImageFile()
    {
        return $this->imageFile;
    }

    function getUser(): ?user
    {
        return $this->user;
    }

    function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    function getCategorie(): Collection
    {
        return $this->categorie;
    }

    function addCategorie(Categorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie[] = $categorie;
        }

        return $this;
    }

    function removeCategorie(Categorie $categorie): self
    {
        $this->categorie->removeElement($categorie);

        return $this;
    }
}
