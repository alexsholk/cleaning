<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InventoryMovementRepository")
 */
class InventoryMovement
{
    // Типы операций
    const TYPE_PURCHASE = 0; // Закупка
    const TYPE_PROVIDE = 1; // Выдача
    const TYPE_RETURN = 2; // Возврат
    const TYPE_CONSUMPTION = 3; // Расход

    const TYPE_CODE_PURCHASE = 'type.purchase';
    const TYPE_CODE_PROVIDE = 'type.provide';
    const TYPE_CODE_RETURN = 'type.return';
    const TYPE_CODE_CONSUMPTION = 'type.consumption';

    public static $types = [
        self::TYPE_PURCHASE => self::TYPE_CODE_PURCHASE,
        self::TYPE_PROVIDE => self::TYPE_CODE_PROVIDE,
        self::TYPE_RETURN => self::TYPE_CODE_RETURN,
        self::TYPE_CONSUMPTION => self::TYPE_CODE_CONSUMPTION,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventory", inversedBy="inventoryMovements")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $inventory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cleaner", inversedBy="inventoryMovements")
     */
    private $cleaner;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @Assert\Range(min=0, max=999999.99)
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $datetime;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({
     *     InventoryMovement::TYPE_PURCHASE,
     *     InventoryMovement::TYPE_PROVIDE,
     *     InventoryMovement::TYPE_RETURN,
     *     InventoryMovement::TYPE_CONSUMPTION,
     * })
     */
    private $type;

    public function getId()
    {
        return $this->id;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getCleaner(): ?Cleaner
    {
        return $this->cleaner;
    }

    public function setCleaner(?Cleaner $cleaner): self
    {
        $this->cleaner = $cleaner;

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

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
