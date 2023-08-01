<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ShoppingListItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShoppingListItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(normalizationContext: ['groups' => ['shoppingListItem']])]
class ShoppingListItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['shoppingList'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?bool $isCompleted = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingListItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?ShoppingList $shoppingList = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?string $custom_name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?string $custom_price = null;

    #[ORM\Column]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): static
    {
        $this->isCompleted = $isCompleted;

        return $this;
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

    public function getShoppingList(): ?ShoppingList
    {
        return $this->shoppingList;
    }

    public function setShoppingList(?ShoppingList $shoppingList): static
    {
        $this->shoppingList = $shoppingList;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->isCompleted = false;
    }

    public function getCustomName(): ?string
    {
        return $this->custom_name;
    }

    public function setCustomName(?string $custom_name): static
    {
        $this->custom_name = $custom_name;

        return $this;
    }

    public function getCustomPrice(): ?string
    {
        return $this->custom_price;
    }

    public function setCustomPrice(?string $custom_price): static
    {
        $this->custom_price = $custom_price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
