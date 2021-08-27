<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DevelopmentApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DevelopmentApplicationRepository::class)
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
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $applicantStreetAddress;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=25)
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
     * @ORM\Column(type="boolean")
     */
    private $consent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;
    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;
    /**
     * @ORM\OneToOne(targetEntity=DevelopmentSolution::class, inversedBy="applicationDevelopment", cascade={"persist", "remove"})
     */
    private $solution;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getConsent(): ?bool
    {
        return $this->consent;
    }

    public function setConsent(bool $consent): self
    {
        $this->consent = $consent;

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
