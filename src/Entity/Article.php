<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Categorie;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
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

    #[ORM\Column(type: 'datetime_immutable', options:
    ['default' => 'CURRENT_TIMESTAMP'])]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $auteur;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $file = null;
    //mise en place du bundle vichuploader pour les images
    #[Vich\UploadableField(mapping: 'articles_images', fileNameProperty: 'file')]
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255,nullable:true)]
    private $videoName;
     //mise en place du bundle vichuploader pour les vidéos
    #[Vich\UploadableField(mapping: 'video', fileNameProperty: 'videoName')]
    private $videoFile = null;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'article')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'articles')]
    private $categorie;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $published =false;


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

    function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }
    //getter et setter de vichuploader
/**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    //getter et setter de vichuploader
/**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $videoFile
     */
    public function setVideoFile (?File $videoFile = null): void
    {
        $this->videoFile = $videoFile;

        if (null !== $videoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getVideoFile(): ?File
    {
        return $this->videoFile;
    }
    public function setVideoName(?string $videoName): void
    {
        $this->videoName = $videoName;
    }

    public function getVideoName(): ?string
    {
        return $this->videoName;
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
    public function __toString()
    {
        return $this->getTitre();
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }
}