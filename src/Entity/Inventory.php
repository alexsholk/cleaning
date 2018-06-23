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

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\Range(min=0, max=9999.99)
     */
    private $step;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $unit;

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

    public function getStep()
    {
        return $this->step;
    }

    public function setStep($step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
