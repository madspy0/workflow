<?php

namespace App\Entity;

use App\Repository\DrawnAreaRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\User as PortalUser;

/**
 * @ORM\Entity(repositoryClass=DrawnAreaRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class DrawnArea
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"geoms"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $localGoverment;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"geoms"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"geoms"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"geoms"})
     */
    private $middlename;

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;
    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable $updatedAt
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated_at", type="datetime_immutable", nullable=true)
     */
    private $updatedAt;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $numberSolution;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $solutedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $archivedAt;
    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"geoms"})
     */
    private $status;

    /**
     * @return mixed
     */
    public function getArchivedAt()
    {
        return $this->archivedAt;
    }

    /**
     * @param mixed $archivedAt
     */
    public function setArchivedAt($archivedAt): void
    {
        $this->archivedAt = $archivedAt;
    }

    /**
     * @ORM\Column(type="geometry")
     * @Groups({"geoms"})
     */
    private $geom;

    /**
     * @ORM\ManyToOne(targetEntity=UsePlantCategory::class)
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $useCategory;
    /**
     * @ORM\ManyToOne(targetEntity=UsePlantSubCategory::class)
     * @ORM\JoinColumn(name="sub_category_id", referencedColumnName="id")
     */
    private $useSubCategory;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $area;

    /**
     * @return mixed
     */
    public function getDocumentsType()
    {
        return $this->documentsType;
    }

    /**
     * @param mixed $documentsType
     */
    public function setDocumentsType($documentsType): void
    {
        $this->documentsType = $documentsType;
    }

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $documentsType;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;

    /**
     * @ORM\OneToOne(targetEntity=ArchiveGround::class, mappedBy="drawnArea", cascade={"persist", "remove"})
     */
    private $archiveGround;
    /**
     * @ORM\OneToOne(targetEntity=ArchiveGroundGov::class, mappedBy="drawnArea", cascade={"persist", "remove"})
     */
    private $archiveGroundGov;
    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area): void
    {
        $this->area = $area;
    }

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getMiddlename(): ?string
    {
        return $this->middlename;
    }

    public function setMiddlename(string $middlename): self
    {
        $this->middlename = $middlename;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNumberSolution(): ?string
    {
        return $this->numberSolution;
    }

    public function setNumberSolution(string $numberSolution): self
    {
        $this->numberSolution = $numberSolution;

        return $this;
    }

    public function getSolutedAt(): ?\DateTimeImmutable
    {
        return $this->solutedAt;
    }

    public function setSolutedAt(\DateTimeImmutable $solutedAt): self
    {
        $this->solutedAt = $solutedAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
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

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): self
    {
        $this->geom = $geom;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new DateTimeImmutable('now');
        $this->setUpdatedAt($dateTimeNow);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function getUseSubCategory(): ?UsePlantSubCategory
    {
        return $this->useSubCategory;
    }

    public function setUseSubCategory(?UsePlantSubCategory $useSubCategory): self
    {
        $this->useSubCategory = $useSubCategory;

        return $this;
    }

    public function getUseCategory(): ?UsePlantCategory
    {
        return $this->useCategory;
    }

    public function setUseCategory(?UsePlantCategory $useCategory): self
    {
        $this->useCategory = $useCategory;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?PortalUser $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getArchiveGround(): ?ArchiveGround
    {
        return $this->archiveGround;
    }

    public function setArchiveGround(?ArchiveGround $archiveGround): self
    {
        // unset the owning side of the relation if necessary
        if ($archiveGround === null && $this->archiveGround !== null) {
            $this->archiveGround->setDrawnArea(null);
        }

        // set the owning side of the relation if necessary
        if ($archiveGround !== null && $archiveGround->getDrawnArea() !== $this) {
            $archiveGround->setDrawnArea($this);
        }

        $this->archiveGround = $archiveGround;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArchiveGroundGov()
    {
        return $this->archiveGroundGov;
    }

    /**
     * @param mixed $archiveGroundGov
     */
    public function setArchiveGroundGov($archiveGroundGov): self
    {
        // unset the owning side of the relation if necessary
        if ($archiveGroundGov === null && $this->archiveGroundGov !== null) {
            $this->archiveGroundGov->setDrawnArea(null);
        }

        // set the owning side of the relation if necessary
        if ($archiveGroundGov !== null && $archiveGroundGov->getDrawnArea() !== $this) {
            $archiveGroundGov->setDrawnArea($this);
        }

        $this->archiveGroundGov = $archiveGroundGov;

        return $this;
    }
}
