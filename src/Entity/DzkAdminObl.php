<?php

namespace App\Entity;

use App\Repository\DzkAdminOblRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DzkAdminOblRepository::class)
 */
class DzkAdminObl
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="name_rgn")
     */
    private $nameRgn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $koatuu2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $admin1c;

    /**
     * @ORM\Column(type="geometry")
     */
    private $geom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameRgn(): ?string
    {
        return $this->nameRgn;
    }

    public function setNameRgn(string $nameRgn): self
    {
        $this->nameRgn = $nameRgn;

        return $this;
    }

    public function getKoatuu2(): ?string
    {
        return $this->koatuu2;
    }

    public function setKoatuu2(string $koatuu2): self
    {
        $this->koatuu2 = $koatuu2;

        return $this;
    }

    public function getAdmin1c(): ?string
    {
        return $this->admin1c;
    }

    public function setAdmin1c(string $admin1c): self
    {
        $this->admin1c = $admin1c;

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
