<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\IngredientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    private ?Product $product = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private string $quantity;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customQuantityType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customName = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3, nullable: true)]
    private ?string $customPrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantityType(): ?string
    {
        return $this->customQuantityType;
    }

    public function setQuantityType(?string $customQuantityType): static
    {
        $this->customQuantityType = $customQuantityType;

        return $this;
    }

    public function getCustomName(): ?string
    {
        return $this->customName;
    }

    public function setCustomName(?string $customName): static
    {
        $this->customName = $customName;

        return $this;
    }

    public function getCustomPrice(): ?string
    {
        return $this->customPrice;
    }

    public function setCustomPrice(?string $customPrice): static
    {
        $this->customPrice = $customPrice;

        return $this;
    }
}
