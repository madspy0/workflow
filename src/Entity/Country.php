<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 * @ORM\Table(name="_countries")
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="country_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_ru;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_ua;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_be;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_en;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_es;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_pt;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_de;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_fr;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_it;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_pl;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_ja;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_lt;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_lv;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title_cz;

    /**
     * @ORM\OneToMany(targetEntity=Region::class, mappedBy="country", orphanRemoval=true)
     */
    private $regions;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
    }

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

    public function setTitleUa(string $title_ua): self
    {
        $this->title_ua = $title_ua;

        return $this;
    }

    public function getTitleBe(): ?string
    {
        return $this->title_be;
    }

    public function setTitleBe(string $title_be): self
    {
        $this->title_be = $title_be;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    public function setTitleEn(string $title_en): self
    {
        $this->title_en = $title_en;

        return $this;
    }

    public function getTitleEs(): ?string
    {
        return $this->title_es;
    }

    public function setTitleEs(string $title_es): self
    {
        $this->title_es = $title_es;

        return $this;
    }

    public function getTitlePt(): ?string
    {
        return $this->title_pt;
    }

    public function setTitlePt(string $title_pt): self
    {
        $this->title_pt = $title_pt;

        return $this;
    }

    public function getTitleDe(): ?string
    {
        return $this->title_de;
    }

    public function setTitleDe(string $title_de): self
    {
        $this->title_de = $title_de;

        return $this;
    }

    public function getTitleFr(): ?string
    {
        return $this->title_fr;
    }

    public function setTitleFr(string $title_fr): self
    {
        $this->title_fr = $title_fr;

        return $this;
    }

    public function getTitleIt(): ?string
    {
        return $this->title_it;
    }

    public function setTitleIt(string $title_it): self
    {
        $this->title_it = $title_it;

        return $this;
    }

    public function getTitlePl(): ?string
    {
        return $this->title_pl;
    }

    public function setTitlePl(string $title_pl): self
    {
        $this->title_pl = $title_pl;

        return $this;
    }

    public function getTitleJa(): ?string
    {
        return $this->title_ja;
    }

    public function setTitleJa(string $title_ja): self
    {
        $this->title_ja = $title_ja;

        return $this;
    }

    public function getTitleLt(): ?string
    {
        return $this->title_lt;
    }

    public function setTitleLt(string $title_lt): self
    {
        $this->title_lt = $title_lt;

        return $this;
    }

    public function getTitleLv(): ?string
    {
        return $this->title_lv;
    }

    public function setTitleLv(string $title_lv): self
    {
        $this->title_lv = $title_lv;

        return $this;
    }

    public function getTitleCz(): ?string
    {
        return $this->title_cz;
    }

    public function setTitleCz(string $title_cz): self
    {
        $this->title_cz = $title_cz;

        return $this;
    }

    /**
     * @return Collection|Region[]
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions[] = $region;
            $region->setCountry($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        if ($this->regions->removeElement($region)) {
            // set the owning side to null (unless already changed)
            if ($region->getCountry() === $this) {
                $region->setCountry(null);
            }
        }

        return $this;
    }
}
