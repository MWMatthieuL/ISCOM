<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable
 * @UniqueEntity("email", message="Cette adresse e-mail est déja utilisée")
 */
class User implements UserInterface, \Serializable
{
    const TYPE_STUDENT = 'student';
    const TYPE_COMPANY = 'company';
    const TYPE_ISCOM = 'admin';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $phone;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * maxSize = "5M",
     * mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     * maxSizeMessage = "La taille maximum autorisée est de 5MB.",
     *
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="picture")
     * @var File|null
     */
    private $imageFile = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture = null;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * maxSize = "10M",
     * mimeTypes = {"application/pdf", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"},
     * maxSizeMessage = "La taille maximum autorisée est de 10MB.",
     *
     * @Vich\UploadableField(mapping="cv_file", fileNameProperty="cvFilename")
     * @var File|null
     */
    private $cvFile = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cvFilename = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $questionnary = [];

    /**
     * @ORM\OneToMany(targetEntity=Matching::class, mappedBy="student", orphanRemoval=true)
     */
    private $matchings;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="company", orphanRemoval=true)
     */
    private $offers;

    public function __construct()
    {
        $this->matchings = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function setImageFile(?File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getDisplayName(): ?string
    {
        return ucfirst(strtolower($this->getFirstName())).' '.strtoupper($this->getLastName());
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function setCvFile(?File $cv = null)
    {
        $this->cvFile = $cv;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($cv) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getCvFile(): ?File
    {
        return $this->cvFile;
    }

    public function getCvFilename(): ?string
    {
        return $this->cvFilename;
    }

    public function setCvFilename(?string $cvFilename): self
    {
        $this->cvFilename = $cvFilename;

        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->phone,
            $this->roles,
            $this->password,
            $this->companyName,
            $this->type,
            $this->picture,
            $this->cvFilename,
            $this->questionnary,
            $this->matchings,
            $this->offers,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->firstName,
            $this->lastName,
            $this->phone,
            $this->email,
            $this->roles,
            $this->password,
            $this->companyName,
            $this->type,
            $this->picture,
            $this->cvFilename,
            $this->questionnary,
            $this->matchings,
            $this->offers,
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getQuestionnary(): ?array
    {
        return $this->questionnary;
    }

    public function setQuestionnary(?array $questionnary): self
    {
        $this->questionnary = $questionnary;

        return $this;
    }

    /**
     * @return Collection|Matching[]
     */
    public function getMatchings(): Collection
    {
        return $this->matchings;
    }

    public function addMatching(Matching $matching): self
    {
        if (!$this->matchings->contains($matching)) {
            $this->matchings[] = $matching;
            $matching->setStudent($this);
        }

        return $this;
    }

    public function removeMatching(Matching $matching): self
    {
        if ($this->matchings->removeElement($matching)) {
            // set the owning side to null (unless already changed)
            if ($matching->getStudent() === $this) {
                $matching->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setCompany($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getCompany() === $this) {
                $offer->setCompany(null);
            }
        }

        return $this;
    }
}
