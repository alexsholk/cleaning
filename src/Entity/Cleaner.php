<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CleanerRepository")
 */
class Cleaner
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
     * @ORM\Column(type="phone_number")
     * @AssertPhoneNumber
     */
    private $phone;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     * @AssertPhoneNumber
     */
    private $additionalPhone;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Order", mappedBy="cleaners")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventoryMovement", mappedBy="cleaner")
     */
    private $inventoryMovements;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
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

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdditionalPhone()
    {
        return $this->additionalPhone;
    }

    public function setAdditionalPhone($additionalPhone): self
    {
        $this->additionalPhone = $additionalPhone;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addCleaner($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            $order->removeCleaner($this);
        }

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
            $inventoryMovement->setCleaner($this);
        }

        return $this;
    }

    public function removeInventoryMovement(InventoryMovement $inventoryMovement): self
    {
        if ($this->inventoryMovements->contains($inventoryMovement)) {
            $this->inventoryMovements->removeElement($inventoryMovement);
            // set the owning side to null (unless already changed)
            if ($inventoryMovement->getCleaner() === $this) {
                $inventoryMovement->setCleaner(null);
            }
        }

        return $this;
    }
}
