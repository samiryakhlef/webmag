<?php

namespace App\Entity;
use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass:UserRepository::class)]
#[ORM\Table(name:'`user`')]
#[UniqueEntity(fields:['email'], message:'There is already an account with this email')]
#[Vich\Uploadable]

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
private $contribution = 0;

#[ORM\Column(type:'text', nullable:true)]
private $aPropos;

#[ORM\Column(type:'string', length:255, options: ['default' => 'test'], nullable:true)]
private $social;

#[Vich\UploadableField(mapping: 'images', fileNameProperty: 'imageFile')]
private ?File $imageFile = null;

#[ORM\Column(type: 'string', length: 255, nullable: true)]
private ?string $imageName = null;

#[ORM\OneToMany(mappedBy:'user', targetEntity:Article::class, orphanRemoval:true)]
private $article;

#[ORM\OneToMany(mappedBy:'user', targetEntity:BlogPost::class, orphanRemoval:true)]
private $blogPosts;

#[ORM\Column(type: 'datetime_immutable', options:
['default' => 'CURRENT_TIMESTAMP'])]
private $UpdatedAt;

public function __construct()
    {
    $this->article = new ArrayCollection();
    $this->blogPosts = new ArrayCollection();
}


function getId(): ?int
    {
    return $this->id;
}
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

public function __toString(): string
    {
        return $this->getNom();
    }

public function getUpdatedAt(): ?\DateTimeImmutable
{
    return $this->UpdatedAt;
}

public function setUpdatedAt(\DateTimeImmutable $UpdatedAt): self
{
    $this->UpdatedAt = $UpdatedAt;

    return $this;
}
}
