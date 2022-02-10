<?php

namespace App\Entity;

use App\Repository\MatchingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchingRepository::class)
 */
class Matching
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="matchings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\Column(type="float")
     */
    private $percentage;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class, inversedBy="matchings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $acceptedByStudent;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sendByCompany;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $concludedByCompany;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): self
    {
        $this->student = $student;

        return $this;
    }


    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getAcceptedByStudent(){
        return $this->acceptedByStudent;
    }

    public function setAcceptedByStudent(string $acceptedByStudent): self
    {
        $this->acceptedByStudent = $acceptedByStudent;
        return $this;
    }

    public function getSendByCompany(){
        return $this->sendByCompany;
    }

    public function setSendByCompany(string $sendByCompany): self
    {
        $this->sendByCompany = $sendByCompany;
        return $this;
    }

    public function getConcludedByCompany(){
        return $this->concludedByCompany;
    }

    public function setConcludedByCompany(string $concludedByCompany): self
    {
        $this->concludedByCompany = $concludedByCompany;
        return $this;
    }
}
