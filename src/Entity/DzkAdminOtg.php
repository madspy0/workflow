<?php

namespace App\Entity;

use App\Repository\DzkAdminOtgRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DzkAdminOtgRepository::class)
 * @ORM\Table(name="dzk_admin_otg")
 */
class DzkAdminOtg
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $kobl;

    /**
     * @ORM\Column(type="string", length=99)
     */
    private $koatuu;

    /**
     * @ORM\Column(type="smallint")
     */
    private $is_enable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_otg;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adm3c;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_obl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adm1c;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_rgn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adm2c;

    /**
     * @ORM\Column(type="geometry")
     */
    private $geom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKobl(): ?int
    {
        return $this->kobl;
    }

    public function setKobl(int $kobl): self
    {
        $this->kobl = $kobl;

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

    public function getIsEnable(): ?int
    {
        return $this->is_enable;
    }

    public function setIsEnable(int $is_enable): self
    {
        $this->is_enable = $is_enable;

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

    public function getNameOtg(): ?string
    {
        return $this->name_otg;
    }

    public function setNameOtg(string $name_otg): self
    {
        $this->name_otg = $name_otg;

        return $this;
    }

    public function getAdm3c(): ?string
    {
        return $this->adm3c;
    }

    public function setAdm3c(string $adm3c): self
    {
        $this->adm3c = $adm3c;

        return $this;
    }

    public function getNameObl(): ?string
    {
        return $this->name_obl;
    }

    public function setNameObl(string $name_obl): self
    {
        $this->name_obl = $name_obl;

        return $this;
    }

    public function getAdm1c(): ?string
    {
        return $this->adm1c;
    }

    public function setAdm1c(string $adm1c): self
    {
        $this->adm1c = $adm1c;

        return $this;
    }

    public function getNameRgn(): ?string
    {
        return $this->name_rgn;
    }

    public function setNameRgn(string $name_rgn): self
    {
        $this->name_rgn = $name_rgn;

        return $this;
    }

    public function getAdm2c(): ?string
    {
        return $this->adm2c;
    }

    public function setAdm2c(string $adm2c): self
    {
        $this->adm2c = $adm2c;

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
}
