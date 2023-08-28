<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\QuantityType;
use App\Repository\IngredientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['ingredient']]
)]
#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ingredient', 'recipe'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[Groups(['ingredient', 'recipe'])]
    private ?Product $product = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    #[Groups(['ingredient', 'recipe'])]
    private string $quantity;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['ingredient', 'recipe'])]
    private ?string $customQuantityType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['ingredient', 'recipe'])]
    private ?string $customName = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3, nullable: true)]
    #[Groups(['ingredient', 'recipe'])]
    private ?string $customPrice = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ingredient', 'recipe'])]
    private ?Recipe $recipe = null;

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

    public function getCustomQuantityType(): ?string
    {
        return $this->customQuantityType;
    }

    public function setCustomQuantityType(?string $customQuantityType): static
    {
        if (!QuantityType::validate($customQuantityType) && $customQuantityType !== null) {
            throw new \InvalidArgumentException('Invalid custom quantity type');
        }
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

    public function getRecipe(): ?recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
