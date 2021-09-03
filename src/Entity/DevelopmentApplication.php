<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DevelopmentApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass=DevelopmentApplicationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class DevelopmentApplication
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
    private $applicantLastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $applicantFirstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $applicantMiddlename;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $applicantStreetAddress;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="city_id")
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     */
    private $region;

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region): void
    {
        $this->region = $region;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $landAddress;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $landCity;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $landRegion;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $landPostal;

    /**
     * @return mixed
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * @param mixed $postal
     */
    public function setPostal($postal): void
    {
        $this->postal = $postal;
    }

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $postal;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $landCountry;

    /**
     * @ORM\Column(type="string", length=13)
     */
    private $cadastreNumber;

    /**
     * @ORM\Column(type="float")
     */
    private $area;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $purpose;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $use;

    /**
     * @ORM\Column(type="boolean")
     */
    private $planingDocumentation;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $typeDocumentation;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status = 'draft';
    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;
    /**
     * @ORM\OneToOne(targetEntity=DevelopmentSolution::class, inversedBy="developmentApplication", cascade={"persist", "remove"})
     */
    private $solution;
    /**
     * @ORM\Column(type="geometry")
     * @Assert\NotBlank()
     * @AcmeAssert\ContainsGeom()
     */
    private $geom;

    /**
     * @return mixed
     */
    public function getGeom()
    {
        return $this->geom;
    }

    /**
     * @param mixed $geom
     */
    public function setGeom($geom): void
    {
        $this->geom = $geom;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplicantLastname(): ?string
    {
        return $this->applicantLastname;
    }

    public function setApplicantLastname(string $applicantLastname): self
    {
        $this->applicantLastname = $applicantLastname;

        return $this;
    }

    public function getApplicantFirstname(): ?string
    {
        return $this->applicantFirstname;
    }

    public function setApplicantFirstname(string $applicantFirstname): self
    {
        $this->applicantFirstname = $applicantFirstname;

        return $this;
    }

    public function getApplicantMiddlename(): ?string
    {
        return $this->applicantMiddlename;
    }

    public function setApplicantMiddlename(string $applicantMiddlename): self
    {
        $this->applicantMiddlename = $applicantMiddlename;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getApplicantStreetAddress(): ?string
    {
        return $this->applicantStreetAddress;
    }

    public function setApplicantStreetAddress(string $applicantStreetAddress): self
    {
        $this->applicantStreetAddress = $applicantStreetAddress;

        return $this;
    }

    public function getLandAddress(): ?string
    {
        return $this->landAddress;
    }

    public function setLandAddress(string $landAddress): self
    {
        $this->landAddress = $landAddress;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getLandCity(): ?string
    {
        return $this->landCity;
    }

    public function setLandCity(string $landCity): self
    {
        $this->landCity = $landCity;

        return $this;
    }

    public function getLandRegion(): ?string
    {
        return $this->landRegion;
    }

    public function setLandRegion(string $landRegion): self
    {
        $this->landRegion = $landRegion;

        return $this;
    }

    public function getLandPostal(): ?string
    {
        return $this->landPostal;
    }

    public function setLandPostal(string $landPostal): self
    {
        $this->landPostal = $landPostal;

        return $this;
    }

    public function getLandCountry(): ?string
    {
        return $this->landCountry;
    }

    public function setLandCountry(string $landCountry): self
    {
        $this->landCountry = $landCountry;

        return $this;
    }

    public function getCadastreNumber(): ?string
    {
        return $this->cadastreNumber;
    }

    public function setCadastreNumber(string $cadastreNumber): self
    {
        $this->cadastreNumber = $cadastreNumber;

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(float $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    public function setPurpose(string $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function getUse(): ?string
    {
        return $this->use;
    }

    public function setUse(string $use): self
    {
        $this->use = $use;

        return $this;
    }

    public function getPlaningDocumentation(): ?bool
    {
        return $this->planingDocumentation;
    }

    public function setPlaningDocumentation(bool $planingDocumentation): self
    {
        $this->planingDocumentation = $planingDocumentation;

        return $this;
    }

    public function getTypeDocumentation(): ?string
    {
        return $this->typeDocumentation;
    }

    public function setTypeDocumentation(?string $typeDocumentation): self
    {
        $this->typeDocumentation = $typeDocumentation;

        return $this;
    }

    public function getSolution(): ?DevelopmentSolution
    {
        return $this->solution;
    }

    public function setSolution(?DevelopmentSolution $solution): self
    {
        $this->solution = $solution;

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
