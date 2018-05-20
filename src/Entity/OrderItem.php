<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderItemRepository")
 * @ORM\Table(indexes={@ORM\Index(name="idx_order_service", columns={"order_id", "service_id"})})
 * @UniqueEntity(fields={"order", "service"})
 */
class OrderItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $service;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=1)
     * @Assert\NotBlank()
     * @Assert\Range(min=0.1, max=999.9)
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\NotBlank()
     * @Assert\Range(min=0.01, max=999.99)
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", precision=2, scale=1)
     * @Assert\NotBlank()
     * @Assert\Range(min=0.1, max=9.9)
     */
    private $multiplier = 1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $order;

    public function getId()
    {
        return $this->id;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMultiplier()
    {
        return $this->multiplier;
    }

    public function setMultiplier($multiplier): self
    {
        $this->multiplier = $multiplier;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }
}
