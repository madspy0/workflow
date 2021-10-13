<?php

namespace App\Entity;

use App\Repository\DrawnAreaRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DrawnAreaRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class DrawnArea
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"geoms"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $localGoverment;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"geoms"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"geoms"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"geoms"})
     */
    private $middlename;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $use;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $numberSolution;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $solutedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"geoms"})
     */
    private $status;

    /**
     * @ORM\Column(type="geometry")
     * @Groups({"geoms"})
     */
    private $geom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocalGoverment(): ?string
    {
        return $this->localGoverment;
    }

    public function setLocalGoverment(string $localGoverment): self
    {
        $this->localGoverment = $localGoverment;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getMiddlename(): ?string
    {
        return $this->middlename;
    }

    public function setMiddlename(string $middlename): self
    {
        $this->middlename = $middlename;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getUse(): ?string
    {
        return $this->use;
    }

    public function setUse(string $use): self
    {
        $this->use = $use;

        return $this;
    }

    public function getNumberSolution(): ?string
    {
        return $this->numberSolution;
    }

    public function setNumberSolution(string $numberSolution): self
    {
        $this->numberSolution = $numberSolution;

        return $this;
    }

    public function getSolutedAt(): ?\DateTimeImmutable
    {
        return $this->solutedAt;
    }

    public function setSolutedAt(\DateTimeImmutable $solutedAt): self
    {
        $this->solutedAt = $solutedAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): self
    {
        $this->geom = $geom;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new \DateTimeImmutable('now');

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }
}
