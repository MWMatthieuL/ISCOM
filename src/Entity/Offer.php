<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 * @Vich\Uploadable
 */
class Offer
{
    const TYPE_WORKSTUDY = 'alternance';
    const TYPE_INTERNSHIP = 'stage';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $questionnary = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity=Matching::class, mappedBy="offer", orphanRemoval=true)
     */
    private $matchings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $provided = false;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * maxSize = "10M",
     * mimeTypes = {"application/pdf", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"},
     * maxSizeMessage = "La taille maximum autorisée est de 10MB.",
     *
     * @Vich\UploadableField(mapping="offer_file", fileNameProperty="filename")
     * @var File|null
     */
    private $offerFile = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;


    public function __construct()
    {
        $this->matchings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionnary(): ?array
    {
        return $this->questionnary;
    }

    public function setQuestionnary(array $questionnary): self
    {
        $this->questionnary = $questionnary;

        return $this;
    }

    public function getCompany(): ?User
    {
        return $this->company;
    }

    public function setCompany(?User $company): self
    {
        $this->company = $company;

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
            $matching->setOffer($this);
        }

        return $this;
    }

    public function removeMatching(Matching $matching): self
    {
        if ($this->matchings->removeElement($matching)) {
            // set the owning side to null (unless already changed)
            if ($matching->getOffer() === $this) {
                $matching->setOffer(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function isProvided(): ?bool
    {
        return $this->provided;
    }

    public function setProvided(?bool $provided): self
    {
        $this->provided = $provided;

        return $this;
    }

    public function setOfferFile(?File $file = null)
    {
        $this->offerFile = $file;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($file) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->publishedAt = new \DateTime('now');
        }
    }

    public function getOfferFile(): ?File
    {
        return $this->offerFile;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
