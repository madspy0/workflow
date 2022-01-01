<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use App\Entity\DzkAdminObl;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 * @Vich\Uploadable
 */
class Profile implements Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $middlename;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="DzkAdminObl")
     * @ORM\JoinColumn(name="oblast_id", referencedColumnName="id")
     */
    private $oblast;

    /**
     * @return mixed
     */
    public function getOblast()
    {
        return $this->oblast;
    }

    /**
     * @param mixed $oblast
     */
    public function setOblast($oblast): void
    {
        $this->oblast = $oblast;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localGoverment;

    /**
     * @return mixed
     */
    public function getLocalGoverment()
    {
        return $this->localGoverment;
    }

    /**
     * @param mixed $localGoverment
     */
    public function setLocalGoverment($localGoverment): void
    {
        $this->localGoverment = $localGoverment;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(User $users): self
    {
        $this->users = $users;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function __construct()
    {
        $this->ecp = new EmbeddedFile();
    }

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(
     *     mapping="user_ecp",
     *     fileNameProperty="ecp.name",
     *     size="ecp.size",
     *     mimeType="ecp.mimeType",
     *     originalName="ecp.originalName",
     *     dimensions="ecp.dimensions")
     *
     * @var File|null
     */
    private $ecpFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    private $ecp;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTimeInterface|null
     */
    private $ecpAt;

    /**
     * @return DateTimeInterface|null
     */
    public function getEcpAt(): ?DateTimeInterface
    {
        return $this->ecpAt;
    }

    /**
     * @return EmbeddedFile
     */
    public function getEcp(): ?EmbeddedFile
    {
        return $this->ecp;
    }

    /**
     * @param EmbeddedFile $ecp
     */
    public function setEcp(EmbeddedFile $ecp): void
    {
        $this->ecp = $ecp;
    }

    /**
     * @return File|null
     */
    public function getEcpFile(): ?File
    {
        return $this->ecpFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $ecpFile
     */
    public function setEcpFile(?File $ecpFile = null)
    {
        $this->ecpFile = $ecpFile;

        if (null !== $ecpFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->ecpAt = new DateTimeImmutable();
        }
    }

    /** @see Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->ecp,

        ));
    }

    /** @see Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->ecp,
            ) = unserialize($serialized);
    }

    /**
     * @param ExecutionContextInterface $context
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->ecpFile->getMimeType() != 'application/pkcs7-signature') {
            $context
                ->buildViolation('Неправильний тип файлу (p7s)')
                ->atPath('ecpFile')
                ->addViolation();
        }
    }

}
