<?php

namespace App\Entity;

use App\Repository\DevelopmentSolutionRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=DevelopmentSolutionRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class DevelopmentSolution
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=DevelopmentApplication::class, inversedBy="solution")
     * @ORM\JoinColumn(name="development_application_id", referencedColumnName="id")
     */
    private $developmentApplication;

     /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    private $solution;

    /**
     * @ORM\Column(type="boolean", name="action")
     */
    private $status = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDevelopmentApplication(): ?DevelopmentApplication
    {
        return $this->developmentApplication;
    }

    public function setDevelopmentApplication(?DevelopmentApplication $developmentApplication): self
    {
        // unset the owning side of the relation if necessary
        if ($developmentApplication === null && $this->developmentApplication !== null) {
            $this->developmentApplication->setSolution(null);
        }

        // set the owning side of the relation if necessary
        if ($developmentApplication !== null && $developmentApplication->getSolution() !== $this) {
            $developmentApplication->addSolution($this);
        }

        $this->developmentApplication = $developmentApplication;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new DateTime('now');

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function getCreatedAt() :?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(string $solution): self
    {
        $this->solution = $solution;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
