<?php

namespace App\Entity;

use App\Repository\ArchiveGroundRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchiveGroundRepository::class)
 */
class ArchiveGround
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $localGoverment;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $documentsType;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $docNumber;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $documentDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\OneToOne(targetEntity=DrawnArea::class, inversedBy="archiveGround", cascade={"persist", "remove"})
     */
    private $drawnArea;

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

    public function getDocumentsType(): ?string
    {
        return $this->documentsType;
    }

    public function setDocumentsType(string $documentsType): self
    {
        $this->documentsType = $documentsType;

        return $this;
    }

    public function getDocNumber(): ?string
    {
        return $this->docNumber;
    }

    public function setDocNumber(string $docNumber): self
    {
        $this->docNumber = $docNumber;

        return $this;
    }

    public function getDocumentDate(): ?\DateTimeImmutable
    {
        return $this->documentDate;
    }

    public function setDocumentDate(\DateTimeImmutable $documentDate): self
    {
        $this->documentDate = $documentDate;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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
