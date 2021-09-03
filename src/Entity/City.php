<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @ORM\Table(name="_cities")
 */
class City
{
    /**
     * @return mixed
     */
    public function getTitleEn()
    {
        return $this->title_en;
    }

    /**
     * @param mixed $title_en
     */
    public function setTitleEn($title_en): void
    {
        $this->title_en = $title_en;
    }

    /**
     * @return mixed
     */
    public function getAreaEn()
    {
        return $this->area_en;
    }

    /**
     * @param mixed $area_en
     */
    public function setAreaEn($area_en): void
    {
        $this->area_en = $area_en;
    }

    /**
     * @return mixed
     */
    public function getRegionEn()
    {
        return $this->region_en;
    }

    /**
     * @param mixed $region_en
     */
    public function setRegionEn($region_en): void
    {
        $this->region_en = $region_en;
    }

    /**
     * @return mixed
     */
    public function getTitleEs()
    {
        return $this->title_es;
    }

    /**
     * @param mixed $title_es
     */
    public function setTitleEs($title_es): void
    {
        $this->title_es = $title_es;
    }

    /**
     * @return mixed
     */
    public function getAreaEs()
    {
        return $this->area_es;
    }

    /**
     * @param mixed $area_es
     */
    public function setAreaEs($area_es): void
    {
        $this->area_es = $area_es;
    }

    /**
     * @return mixed
     */
    public function getRegionEs()
    {
        return $this->region_es;
    }

    /**
     * @param mixed $region_es
     */
    public function setRegionEs($region_es): void
    {
        $this->region_es = $region_es;
    }

    /**
     * @return mixed
     */
    public function getTitlePt()
    {
        return $this->title_pt;
    }

    /**
     * @param mixed $title_pt
     */
    public function setTitlePt($title_pt): void
    {
        $this->title_pt = $title_pt;
    }

    /**
     * @return mixed
     */
    public function getAreaPt()
    {
        return $this->area_pt;
    }

    /**
     * @param mixed $area_pt
     */
    public function setAreaPt($area_pt): void
    {
        $this->area_pt = $area_pt;
    }

    /**
     * @return mixed
     */
    public function getRegionPt()
    {
        return $this->region_pt;
    }

    /**
     * @param mixed $region_pt
     */
    public function setRegionPt($region_pt): void
    {
        $this->region_pt = $region_pt;
    }

    /**
     * @return mixed
     */
    public function getTitleDe()
    {
        return $this->title_de;
    }

    /**
     * @param mixed $title_de
     */
    public function setTitleDe($title_de): void
    {
        $this->title_de = $title_de;
    }

    /**
     * @return mixed
     */
    public function getAreaDe()
    {
        return $this->area_de;
    }

    /**
     * @param mixed $area_de
     */
    public function setAreaDe($area_de): void
    {
        $this->area_de = $area_de;
    }

    /**
     * @return mixed
     */
    public function getRegionDe()
    {
        return $this->region_de;
    }

    /**
     * @param mixed $region_de
     */
    public function setRegionDe($region_de): void
    {
        $this->region_de = $region_de;
    }

    /**
     * @return mixed
     */
    public function getTitleFr()
    {
        return $this->title_fr;
    }

    /**
     * @param mixed $title_fr
     */
    public function setTitleFr($title_fr): void
    {
        $this->title_fr = $title_fr;
    }

    /**
     * @return mixed
     */
    public function getAreaFr()
    {
        return $this->area_fr;
    }

    /**
     * @param mixed $area_fr
     */
    public function setAreaFr($area_fr): void
    {
        $this->area_fr = $area_fr;
    }

    /**
     * @return mixed
     */
    public function getRegionFr()
    {
        return $this->region_fr;
    }

    /**
     * @param mixed $region_fr
     */
    public function setRegionFr($region_fr): void
    {
        $this->region_fr = $region_fr;
    }

    /**
     * @return mixed
     */
    public function getTitleIt()
    {
        return $this->title_it;
    }

    /**
     * @param mixed $title_it
     */
    public function setTitleIt($title_it): void
    {
        $this->title_it = $title_it;
    }

    /**
     * @return mixed
     */
    public function getAreaIt()
    {
        return $this->area_it;
    }

    /**
     * @param mixed $area_it
     */
    public function setAreaIt($area_it): void
    {
        $this->area_it = $area_it;
    }

    /**
     * @return mixed
     */
    public function getRegionIt()
    {
        return $this->region_it;
    }

    /**
     * @param mixed $region_it
     */
    public function setRegionIt($region_it): void
    {
        $this->region_it = $region_it;
    }

    /**
     * @return mixed
     */
    public function getTitlePl()
    {
        return $this->title_pl;
    }

    /**
     * @param mixed $title_pl
     */
    public function setTitlePl($title_pl): void
    {
        $this->title_pl = $title_pl;
    }

    /**
     * @return mixed
     */
    public function getAreaPl()
    {
        return $this->area_pl;
    }

    /**
     * @param mixed $area_pl
     */
    public function setAreaPl($area_pl): void
    {
        $this->area_pl = $area_pl;
    }

    /**
     * @return mixed
     */
    public function getRegionPl()
    {
        return $this->region_pl;
    }

    /**
     * @param mixed $region_pl
     */
    public function setRegionPl($region_pl): void
    {
        $this->region_pl = $region_pl;
    }

    /**
     * @return mixed
     */
    public function getTitleJa()
    {
        return $this->title_ja;
    }

    /**
     * @param mixed $title_ja
     */
    public function setTitleJa($title_ja): void
    {
        $this->title_ja = $title_ja;
    }

    /**
     * @return mixed
     */
    public function getAreaJa()
    {
        return $this->area_ja;
    }

    /**
     * @param mixed $area_ja
     */
    public function setAreaJa($area_ja): void
    {
        $this->area_ja = $area_ja;
    }

    /**
     * @return mixed
     */
    public function getRegionJa()
    {
        return $this->region_ja;
    }

    /**
     * @param mixed $region_ja
     */
    public function setRegionJa($region_ja): void
    {
        $this->region_ja = $region_ja;
    }

    /**
     * @return mixed
     */
    public function getTitleLt()
    {
        return $this->title_lt;
    }

    /**
     * @param mixed $title_lt
     */
    public function setTitleLt($title_lt): void
    {
        $this->title_lt = $title_lt;
    }

    /**
     * @return mixed
     */
    public function getAreaLt()
    {
        return $this->area_lt;
    }

    /**
     * @param mixed $area_lt
     */
    public function setAreaLt($area_lt): void
    {
        $this->area_lt = $area_lt;
    }

    /**
     * @return mixed
     */
    public function getRegionLt()
    {
        return $this->region_lt;
    }

    /**
     * @param mixed $region_lt
     */
    public function setRegionLt($region_lt): void
    {
        $this->region_lt = $region_lt;
    }

    /**
     * @return mixed
     */
    public function getTitleLv()
    {
        return $this->title_lv;
    }

    /**
     * @param mixed $title_lv
     */
    public function setTitleLv($title_lv): void
    {
        $this->title_lv = $title_lv;
    }

    /**
     * @return mixed
     */
    public function getAreaLv()
    {
        return $this->area_lv;
    }

    /**
     * @param mixed $area_lv
     */
    public function setAreaLv($area_lv): void
    {
        $this->area_lv = $area_lv;
    }

    /**
     * @return mixed
     */
    public function getRegionLv()
    {
        return $this->region_lv;
    }

    /**
     * @param mixed $region_lv
     */
    public function setRegionLv($region_lv): void
    {
        $this->region_lv = $region_lv;
    }

    /**
     * @return mixed
     */
    public function getTitleCz()
    {
        return $this->title_cz;
    }

    /**
     * @param mixed $title_cz
     */
    public function setTitleCz($title_cz): void
    {
        $this->title_cz = $title_cz;
    }

    /**
     * @return mixed
     */
    public function getAreaCz()
    {
        return $this->area_cz;
    }

    /**
     * @param mixed $area_cz
     */
    public function setAreaCz($area_cz): void
    {
        $this->area_cz = $area_cz;
    }

    /**
     * @return mixed
     */
    public function getRegionCz()
    {
        return $this->region_cz;
    }

    /**
     * @param mixed $region_cz
     */
    public function setRegionCz($region_cz): void
    {
        $this->region_cz = $region_cz;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="city_id")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class)
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class)
     * @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_ru;

    public function __toString()
    {
        return $this->getTitleUa();
    }

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_ru;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_ua;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_ua;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_ua;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_be;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_be;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_be;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_en;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_en;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_en;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_es;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_es;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_es;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_pt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_pt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_pt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_de;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_de;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_de;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_fr;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_fr;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_fr;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_it;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_it;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_it;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_pl;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_pl;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_pl;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_ja;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_ja;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_ja;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_lt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_lt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_lt;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_lv;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_lv;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_lv;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title_cz;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $area_cz;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $region_cz;
    public function getId(): ?int
    {
        return $this->id;
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
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

    public function getAreaRu(): ?string
    {
        return $this->area_ru;
    }

    public function setAreaRu(string $area_ru): self
    {
        $this->area_ru = $area_ru;

        return $this;
    }

    public function getRegionRu(): ?string
    {
        return $this->region_ru;
    }

    public function setRegionRu(string $region_ru): self
    {
        $this->region_ru = $region_ru;

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

    public function getAreaUa(): ?string
    {
        return $this->area_ua;
    }

    public function setAreaUa(string $area_ua): self
    {
        $this->area_ua = $area_ua;

        return $this;
    }

    public function getRegionUa(): ?string
    {
        return $this->region_ua;
    }

    public function setRegionUa(string $region_ua): self
    {
        $this->region_ua = $region_ua;

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

    public function getAreaBe(): ?string
    {
        return $this->area_be;
    }

    public function setAreaBe(string $area_be): self
    {
        $this->area_be = $area_be;

        return $this;
    }

    public function getRegionBe(): ?string
    {
        return $this->region_be;
    }

    public function setRegionBe(string $region_be): self
    {
        $this->region_be = $region_be;

        return $this;
    }
}
