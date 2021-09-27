<?php

namespace App\Entity;

use App\Repository\CouncilSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CouncilSessionRepository::class)
 */
class CouncilSession
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"dates"})
     * @SerializedName("startDate")
     */
    private $isAt;

    /**
     * @ORM\OneToMany(targetEntity=DevelopmentApplication::class, mappedBy="councilSession")
     */
    private $developmentApplications;

    public function __construct()
    {
        $this->developmentApplications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsAt(): ?\DateTime
    {
        return $this->isAt;
    }

    public function setIsAt(\DateTime $isAt): self
    {
        $this->isAt = $isAt;

        return $this;
    }

    /**
     * @return Collection|DevelopmentApplication[]
     */
    public function getDevelopmentApplications(): Collection
    {
        return $this->developmentApplications;
    }

    public function addDevelopmentApplication(DevelopmentApplication $developmentApplication): self
    {
        if (!$this->developmentApplications->contains($developmentApplication)) {
            $this->developmentApplications[] = $developmentApplication;
            $developmentApplication->setCouncilSession($this);
        }

        return $this;
    }

    public function removeDevelopmentApplication(DevelopmentApplication $developmentApplication): self
    {
        if ($this->developmentApplications->removeElement($developmentApplication)) {
            // set the owning side to null (unless already changed)
            if ($developmentApplication->getCouncilSession() === $this) {
                $developmentApplication->setCouncilSession(null);
            }
        }

        return $this;
    }
}
