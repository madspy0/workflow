<?php

namespace App\Entity;

use App\Repository\ArchiveGroundGovRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchiveGroundGovRepository::class)
 */
class ArchiveGroundGov
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $cadnum;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $registrationAt;

    /**
     * @ORM\OneToOne(targetEntity=DrawnArea::class, inversedBy="archiveGroundGov", cascade={"persist", "remove"})
     */
    private $drawnArea;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCadnum(): ?string
    {
        return $this->cadnum;
    }

    public function setCadnum(?string $cadnum): self
    {
        $this->cadnum = $cadnum;

        return $this;
    }

    public function getRegistrationAt(): ?\DateTimeImmutable
    {
        return $this->registrationAt;
    }

    public function setRegistrationAt(?\DateTimeImmutable $registrationAt): self
    {
        $this->registrationAt = $registrationAt;

        return $this;
    }



    public function getDrawnArea(): ?DrawnArea
    {
        return $this->drawnArea;
    }

    public function setDrawnArea(?DrawnArea $drawnArea): self
    {
        $this->drawnArea = $drawnArea;

        return $this;
    }
}
