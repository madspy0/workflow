<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Gedmo\Loggable
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Gedmo\Versioned
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=Profile::class, mappedBy="users", cascade={"persist", "remove"})
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="DrawnArea", mappedBy="author", fetch="EXTRA_LAZY")
     */
    private $drawnAreas;

    /**
     * @return Collection
     */
    public function getDrawnAreas(): Collection
    {
        return $this->drawnAreas;
    }

    public function __construct() {
        $this->drawnAreas = new ArrayCollection();
    }
//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $isVerified = false;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private bool $isDisabled = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isExpired = false;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Gedmo\Versioned
     */
    private $current_at;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        // set the owning side of the relation if necessary
        if ($profile->getUsers() !== $this) {
            $profile->setUsers($this);
        }

        $this->profile = $profile;

        return $this;
    }

//    public function isVerified(): bool
//    {
//        return $this->isVerified;
//    }
//
//    public function setIsVerified(bool $isVerified): self
//    {
//        $this->isVerified = $isVerified;
//
//        return $this;
//    }

    public function IsDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(?bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    public function IsExpired(): ?bool
    {
        return $this->isExpired;
    }

    public function setIsExpired(?bool $isExpired): self
    {
        $this->isExpired = $isExpired;

        return $this;
    }

    public function getCurrentAt(): ?\DateTimeImmutable
    {
        return $this->current_at;
    }

    public function setCurrentAt(\DateTimeImmutable $current_at): self
    {
        $this->current_at = $current_at;

        return $this;
    }
}
