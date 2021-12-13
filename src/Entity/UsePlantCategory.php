<?php

namespace App\Entity;

use App\Repository\UsePlantCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsePlantCategoryRepository::class)
 * @ORM\Table (name="dzk_land_category")
 */
class UsePlantCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="name")
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=UsePlantSubCategory::class, mappedBy="category")
     */
    private $usePlantSubCategories;

    public function __construct()
    {
        $this->usePlantSubCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|UsePlantSubCategory[]
     */
    public function getUsePlantSubCategories(): Collection
    {
        return $this->usePlantSubCategories;
    }

    public function addUsePlantSubCategory(UsePlantSubCategory $usePlantSubCategory): self
    {
        if (!$this->usePlantSubCategories->contains($usePlantSubCategory)) {
            $this->usePlantSubCategories[] = $usePlantSubCategory;
            $usePlantSubCategory->setCategory($this);
        }

        return $this;
    }

    public function removeUsePlantSubCategory(UsePlantSubCategory $usePlantSubCategory): self
    {
        if ($this->usePlantSubCategories->removeElement($usePlantSubCategory)) {
            // set the owning side to null (unless already changed)
            if ($usePlantSubCategory->getCategory() === $this) {
                $usePlantSubCategory->setCategory(null);
            }
        }

        return $this;
    }
}
