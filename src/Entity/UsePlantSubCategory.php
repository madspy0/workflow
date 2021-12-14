<?php

namespace App\Entity;

use App\Repository\UsePlantSubCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsePlantSubCategoryRepository::class)
 * @ORM\Table (name="public.dzk_land_subcategory")
 */
class UsePlantSubCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=510, name="name")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=UsePlantCategory::class, inversedBy="usePlantSubCategories")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     */
    private $category;

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

    public function getCategory(): ?UsePlantCategory
    {
        return $this->category;
    }

    public function setCategory(?UsePlantCategory $category): self
    {
        $this->category = $category;

        return $this;
    }
}
