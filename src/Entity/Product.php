<?php

namespace App\Entity;

use App\Form\Type\CurrencyTypeEnum;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"create"})
     */
    private $name;

    /**
     * A product may or may not have an associated category
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(groups={"create"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"create"})
     */
    private $currency;

    /**
     * @ORM\Column(type="boolean")
     */
    private $featured;

    public function __construct()
    {
        $this->featured = false;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     * @return $this
     */
    public function setCurrency(?string $currency): self
    {
        if (!in_array($currency, CurrencyTypeEnum::getAvailableTypes())) {
            throw new \InvalidArgumentException("Invalid currency");
        }

        $this->currency = $currency;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    /**
     * @param bool $featured
     * @return $this
     */
    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }
}
