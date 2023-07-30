<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ShoppingListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShoppingListRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(normalizationContext: ['groups' => ['shoppingList']])]
class ShoppingList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['shoppingList'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['shoppingList'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['shoppingList'])]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\OneToMany(mappedBy: 'shoppingList', targetEntity: ShoppingListItem::class, orphanRemoval: true)]
    #[Groups(['shoppingList'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): static
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingListItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ShoppingListItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setShoppingList($this);
        }

        return $this;
    }

    public function removeItem(ShoppingListItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getShoppingList() === $this) {
                $item->setShoppingList(null);
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->dateAdd = new \DateTime();
    }
}
