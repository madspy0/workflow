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
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\OneToOne(targetEntity=DevelopmentApplication::class, mappedBy="solution", cascade={"persist", "remove"})
     */
    private $developmentApplication;

     /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

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
            $developmentApplication->setSolution($this);
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
}
