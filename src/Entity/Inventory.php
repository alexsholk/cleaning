<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InventoryRepository")
 */
class Inventory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventoryMovement", mappedBy="inventory", orphanRemoval=true)
     */
    private $inventoryMovements;

    public function __construct()
    {
        $this->inventoryMovements = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|InventoryMovement[]
     */
    public function getInventoryMovements(): Collection
    {
        return $this->inventoryMovements;
    }

    public function addInventoryMovement(InventoryMovement $inventoryMovement): self
    {
        if (!$this->inventoryMovements->contains($inventoryMovement)) {
            $this->inventoryMovements[] = $inventoryMovement;
            $inventoryMovement->setInventory($this);
        }

        return $this;
    }

    public function removeInventoryMovement(InventoryMovement $inventoryMovement): self
    {
        if ($this->inventoryMovements->contains($inventoryMovement)) {
            $this->inventoryMovements->removeElement($inventoryMovement);
            // set the owning side to null (unless already changed)
            if ($inventoryMovement->getInventory() === $this) {
                $inventoryMovement->setInventory(null);
            }
        }

        return $this;
    }
}
