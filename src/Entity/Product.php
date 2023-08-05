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
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?float $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?\DateTimeInterface $date_update = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?Shop $shop = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]

    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?string $quantity = null;

    #[ORM\Column(length: 20, nullable: true)]

    #[Groups(['shoppingListItem', 'shoppingList'])]
    private ?string $quantityType = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ShoppingListItem::class)]
    private Collection $shoppingListItems;

    public function __construct()
    {
        $this->shoppingListItems = new ArrayCollection();
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
}
