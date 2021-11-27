<?php

namespace App\Entity;

use App\Repository\TownRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TownRepository::class)
 * @ORM\Table(name="town")
 */
class Town
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",name="objectid")
     */
    private $objectId;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"searchOut"})
     */
    private $nameUa;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $typeNp;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $koatuu;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $topocode;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups({"searchOut"})
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $koatuuD;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups({"searchOut"})
     */
    private $nameObl;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $koatuuO;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $perimeter;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etId;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $etX;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $etY;

    /**
     * @ORM\Column(type="geometry", nullable=true)
     */
    private $geom;

    /**
     * @ORM\Column(type="geometry", nullable=true)
     * @Groups({"searchOut"})
     */
    private $geom42;

    /**
     * @ORM\Column(type="geometry", nullable=true)
     */
    private $bbox;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateC;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectId(): ?int
    {
        return $this->objectId;
    }

    public function setObjectId(int $objectId): self
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function getNameUa(): ?string
    {
        return $this->nameUa;
    }

    public function setNameUa(string $nameUa): self
    {
        $this->nameUa = $nameUa;

        return $this;
    }

    public function getTypeNp(): ?string
    {
        return $this->typeNp;
    }

    public function setTypeNp(string $typeNp): self
    {
        $this->typeNp = $typeNp;

        return $this;
    }

    public function getKoatuu(): ?string
    {
        return $this->koatuu;
    }

    public function setKoatuu(string $koatuu): self
    {
        $this->koatuu = $koatuu;

        return $this;
    }

    public function getTopocode(): ?string
    {
        return $this->topocode;
    }

    public function setTopocode(?string $topocode): self
    {
        $this->topocode = $topocode;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getKoatuuD(): ?string
    {
        return $this->koatuuD;
    }

    public function setKoatuuD(?string $koatuuD): self
    {
        $this->koatuuD = $koatuuD;

        return $this;
    }

    public function getNameObl(): ?string
    {
        return $this->nameObl;
    }

    public function setNameObl(?string $nameObl): self
    {
        $this->nameObl = $nameObl;

        return $this;
    }

    public function getKoatuuO(): ?string
    {
        return $this->koatuuO;
    }

    public function setKoatuuO(string $koatuuO): self
    {
        $this->koatuuO = $koatuuO;

        return $this;
    }

    public function getPerimeter(): ?float
    {
        return $this->perimeter;
    }

    public function setPerimeter(?float $perimeter): self
    {
        $this->perimeter = $perimeter;

        return $this;
    }

    public function getEtId(): ?int
    {
        return $this->etId;
    }

    public function setEtId(?int $etId): self
    {
        $this->etId = $etId;

        return $this;
    }

    public function getEtX(): ?float
    {
        return $this->etX;
    }

    public function setEtX(?float $etX): self
    {
        $this->etX = $etX;

        return $this;
    }

    public function getEtY(): ?float
    {
        return $this->etY;
    }

    public function setEtY(?float $etY): self
    {
        $this->etY = $etY;

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

    public function getGeom42()
    {
        return $this->geom42;
    }

    public function setGeom42($geom42): self
    {
        $this->geom42 = $geom42;

        return $this;
    }

    public function getBbox()
    {
        return $this->bbox;
    }

    public function setBbox($bbox): self
    {
        $this->bbox = $bbox;

        return $this;
    }

    public function getDateC(): ?\DateTimeInterface
    {
        return $this->dateC;
    }

    public function setDateC(\DateTimeInterface $dateC): self
    {
        $this->dateC = $dateC;

        return $this;
    }
}
