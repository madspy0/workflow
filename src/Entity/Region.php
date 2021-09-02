<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @ORM\Table(name="_regions")
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="region_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $title_ru;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_ua;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_be;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_en;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_es;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_pl;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_de;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_fr;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_pt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_it;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_ja;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_lt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_lv;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="regions")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     */
    private $country;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleRu(): ?string
    {
        return $this->title_ru;
    }

    public function setTitleRu(string $title_ru): self
    {
        $this->title_ru = $title_ru;

        return $this;
    }

    public function getTitleUa(): ?string
    {
        return $this->title_ua;
    }

    public function setTitleUa(?string $title_ua): self
    {
        $this->title_ua = $title_ua;

        return $this;
    }

    public function getTitleBe(): ?string
    {
        return $this->title_be;
    }

    public function setTitleBe(?string $title_be): self
    {
        $this->title_be = $title_be;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    public function setTitleEn(?string $title_en): self
    {
        $this->title_en = $title_en;

        return $this;
    }

    public function getTitleEs(): ?string
    {
        return $this->title_es;
    }

    public function setTitleEs(?string $title_es): self
    {
        $this->title_es = $title_es;

        return $this;
    }

    public function getTitlePl(): ?string
    {
        return $this->title_pl;
    }

    public function setTitlePl(?string $title_pl): self
    {
        $this->title_pl = $title_pl;

        return $this;
    }

    public function getTitleDe(): ?string
    {
        return $this->title_de;
    }

    public function setTitleDe(?string $title_de): self
    {
        $this->title_de = $title_de;

        return $this;
    }

    public function getTitleFr(): ?string
    {
        return $this->title_fr;
    }

    public function setTitleFr(?string $title_fr): self
    {
        $this->title_fr = $title_fr;

        return $this;
    }

    public function getTitlePt(): ?string
    {
        return $this->title_pt;
    }

    public function setTitlePt(?string $title_pt): self
    {
        $this->title_pt = $title_pt;

        return $this;
    }

    public function getTitleIt(): ?string
    {
        return $this->title_it;
    }

    public function setTitleIt(?string $title_it): self
    {
        $this->title_it = $title_it;

        return $this;
    }

    public function getTitleJa(): ?string
    {
        return $this->title_ja;
    }

    public function setTitleJa(?string $title_ja): self
    {
        $this->title_ja = $title_ja;

        return $this;
    }

    public function getTitleLt(): ?string
    {
        return $this->title_lt;
    }

    public function setTitleLt(?string $title_lt): self
    {
        $this->title_lt = $title_lt;

        return $this;
    }

    public function getTitleLv(): ?string
    {
        return $this->title_lv;
    }

    public function setTitleLv(?string $title_lv): self
    {
        $this->title_lv = $title_lv;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}
