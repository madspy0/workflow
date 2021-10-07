<?php

namespace App\Entity;

use App\Repository\DevelopmentApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\ContainsGeom;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"geoms"})
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=20, nullable="true")
     */
    private $appealNumber;

    /**
     * @return mixed
     */
    public function getAppealNumber()
    {
        return $this->appealNumber;
    }

    /**
     * @param mixed $appealNumber
     */
    public function setAppealNumber($appealNumber): void
    {
        $this->appealNumber = $appealNumber;
    }
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"geoms"})
     */
    private $applicantLastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"geoms"})
     */
    private $applicantFirstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"geoms"})
     */
    private $applicantMiddlename;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $applicantStreetAddress;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $applicantBuild;

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
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="land_city_id", referencedColumnName="city_id")
     */
    private $landCity;

    /**
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="land_region_id", referencedColumnName="region_id")
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
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="land_country_id", referencedColumnName="country_id")
     */
    private $landCountry;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $landApplicantBuild;

    /**
     * @return mixed
     */
    public function getApplicantBuild()
    {
        return $this->applicantBuild;
    }

    /**
     * @param mixed $applicantBuild
     */
    public function setApplicantBuild($applicantBuild): void
    {
        $this->applicantBuild = $applicantBuild;
    }

    /**
     * @return mixed
     */
    public function getLandApplicantBuild()
    {
        return $this->landApplicantBuild;
    }

    /**
     * @param mixed $landApplicantBuild
     */
    public function setLandApplicantBuild($landApplicantBuild): void
    {
        $this->landApplicantBuild = $landApplicantBuild;
    }
    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    private $cadastreNumber;

    /**
     * @ORM\Column(type="float")
     * @Groups({"geoms"})
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
    private $status;
    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;
    /**
     * @ORM\OneToMany(targetEntity=DevelopmentSolution::class, mappedBy="developmentApplication", orphanRemoval=true)
     */
    private $solution;
    /**
     * @ORM\Column(type="geometry")
     * @Groups({"geoms"})
     * @Assert\NotBlank(message="Намалюйте план ділянки")
     * @ContainsGeom(message="Геометрія невірна")
     */
    private $geom;

    /**
     * @ORM\ManyToOne(targetEntity=CouncilSession::class, inversedBy="developmentApplications")
     */
    private $councilSession;

    public function __construct()
    {
        $this->solution = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getGeom(): ?string
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
    public function getCountry(): ?Country
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

    public function getLandCity(): ?City
    {
        return $this->landCity;
    }

    public function setLandCity(City $landCity): self
    {
        $this->landCity = $landCity;

        return $this;
    }

    public function getLandRegion(): ?Region
    {
        return $this->landRegion;
    }

    public function setLandRegion(Region $landRegion): self
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

    public function getLandCountry(): ?Country
    {
        return $this->landCountry;
    }

    public function setLandCountry(Country $landCountry): self
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

    /**
     * @return Collection
     */
    public function getSolution(): Collection
    {
        return $this->solution;
    }

    public function addSolution(DevelopmentSolution $solution): self
    {
        if (!$this->solution->contains($solution)) {
            $this->solution[] = $solution;
            $solution->setDevelopmentApplication($this);
        }

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

    public function getCouncilSession(): ?CouncilSession
    {
        return $this->councilSession;
    }

    public function setCouncilSession(?CouncilSession $councilSession): self
    {
        $this->councilSession = $councilSession;

        return $this;
    }
}
