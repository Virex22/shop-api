<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Enum\QuantityType;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'name' => 'partial',
        'shop' => 'exact',
    ]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['shoppingListItem', 'shoppingList', 'ingredient'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['shoppingListItem', 'shoppingList', 'ingredient'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['shoppingListItem', 'shoppingList', 'ingredient'])]
    private ?float $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['shoppingListItem', 'shoppingList', 'ingredient'])]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['shoppingListItem', 'shoppingList', 'ingredient'])]
    private ?\DateTimeInterface $date_update = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['shoppingListItem', 'shoppingList', 'ingredient'])]
    private ?Shop $shop = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    #[Groups(['shoppingListItem', 'shoppingList', 'ingredient'])]
    private ?string $quantity = null;

    #[ORM\Column(length: 20, nullable: true)]

    #[Groups(['shoppingListItem', 'shoppingList', 'ingredient'])]
    private ?string $quantityType = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ShoppingListItem::class, cascade: ['remove'])]
    private Collection $shoppingListItems;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Ingredient::class)]
    private Collection $ingredients;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: PriceHistory::class, orphanRemoval: true)]
    private Collection $priceHistories;

    public function __construct()
    {
        $this->shoppingListItems = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $this->priceHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): static
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): static
    {
        $this->date_update = $date_update;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): static
    {
        $this->shop = $shop;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->dateAdd = new \DateTime();
        $this->date_update = new \DateTime();
        $priceHistory = new PriceHistory();
        $priceHistory->setDateUpdate($this->date_update);
        $priceHistory->setPrice($this->price);
        $priceHistory->setProduct($this);
        $this->addPriceHistory($priceHistory);
    }

    public function getQuantity(): ?string
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
        return $this->quantityType;
    }

    public function setQuantityType(?string $quantityType): static
    {
        if (!QuantityType::validate($quantityType) && $quantityType !== null) {
            throw new \InvalidArgumentException('Invalid quantity type');
        }
        $this->quantityType = $quantityType;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingListItem>
     */
    public function getShoppingListItems(): Collection
    {
        return $this->shoppingListItems;
    }

    public function addShoppingListItem(ShoppingListItem $shoppingListItem): static
    {
        if (!$this->shoppingListItems->contains($shoppingListItem)) {
            $this->shoppingListItems->add($shoppingListItem);
            $shoppingListItem->setProduct($this);
        }

        return $this;
    }

    public function removeShoppingListItem(ShoppingListItem $shoppingListItem): static
    {
        if ($this->shoppingListItems->removeElement($shoppingListItem)) {
            // set the owning side to null (unless already changed)
            if ($shoppingListItem->getProduct() === $this) {
                $shoppingListItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setProduct($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getProduct() === $this) {
                $ingredient->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PriceHistory>
     */
    public function getPriceHistories(): Collection
    {
        return $this->priceHistories;
    }

    public function addPriceHistory(PriceHistory $priceHistory): static
    {
        if (!$this->priceHistories->contains($priceHistory)) {
            $this->priceHistories->add($priceHistory);
            $priceHistory->setProduct($this);
        }

        return $this;
    }

    public function removePriceHistory(PriceHistory $priceHistory): static
    {
        if ($this->priceHistories->removeElement($priceHistory)) {
            // set the owning side to null (unless already changed)
            if ($priceHistory->getProduct() === $this) {
                $priceHistory->setProduct(null);
            }
        }

        return $this;
    }
}
