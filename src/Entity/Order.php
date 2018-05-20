<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
{
    const DEFAULT_CITY = 'Минск';

    // Статусы заказа
    const STATUS_NEW      = 0; // Создан
    const STATUS_PROCESS  = 1; // В обработке
    const STATUS_EXEC     = 2; // На исполнении
    const STATUS_COMPLETE = 3; // Завершен
    const STATUS_CANCEL   = 4; // Отменен

    const STATUS_CODE_NEW      = 'status.new';
    const STATUS_CODE_PROCESS  = 'status.process';
    const STATUS_CODE_EXEC     = 'status.exec';
    const STATUS_CODE_COMPLETE = 'status.complete';
    const STATUS_CODE_CANCEL   = 'status.cancel';

    public static $statuses = [
        self::STATUS_NEW      => self::STATUS_CODE_NEW,
        self::STATUS_PROCESS  => self::STATUS_CODE_PROCESS,
        self::STATUS_EXEC     => self::STATUS_CODE_EXEC,
        self::STATUS_COMPLETE => self::STATUS_CODE_CANCEL,
        self::STATUS_CANCEL   => self::STATUS_CODE_CANCEL,
    ];

    // Периодичность уборки
    const FREQUENCY_ONCE            = 0; // Один раз
    const FREQUENCY_MONTHLY         = 1; // Раз в месяц
    const FREQUENCY_EVERY_TWO_WEEKS = 2; // Раз в 2 недели
    const FREQUENCY_WEEKLY          = 3; // Раз в неделю

    const FREQUENCY_CODE_ONCE            = 'frequency.once';
    const FREQUENCY_CODE_MONTHLY         = 'frequency.monthly';
    const FREQUENCY_CODE_EVERY_TWO_WEEKS = 'frequency.every_two_weeks';
    const FREQUENCY_CODE_WEEKLY          = 'frequency.weekly';

    public static $frequencies = [
        self::FREQUENCY_ONCE            => self::FREQUENCY_CODE_ONCE,
        self::FREQUENCY_MONTHLY         => self::FREQUENCY_CODE_MONTHLY,
        self::FREQUENCY_EVERY_TWO_WEEKS => self::FREQUENCY_CODE_EVERY_TWO_WEEKS,
        self::FREQUENCY_WEEKLY          => self::FREQUENCY_CODE_WEEKLY,
    ];

    // Способы оплаты
    const PAYMENT_METHOD_CASH     = 0; // Наличные
    const PAYMENT_METHOD_NON_CASH = 1; // Безналичный расчет

    const PAYMENT_METHOD_CODE_CASH     = 'payment_method.cash';
    const PAYMENT_METHOD_CODE_NON_CASH = 'payment_method.non_cash';

    public static $paymentMethods = [
        self::PAYMENT_METHOD_CASH     => self::PAYMENT_METHOD_CODE_CASH,
        self::PAYMENT_METHOD_NON_CASH => self::PAYMENT_METHOD_CODE_NON_CASH,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $datetime;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({
     *     Order::FREQUENCY_ONCE,
     *     Order::FREQUENCY_MONTHLY,
     *     Order::FREQUENCY_EVERY_TWO_WEEKS,
     *     Order::FREQUENCY_WEEKLY,
     * })
     */
    private $frequency = self::FREQUENCY_ONCE;

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
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $city = self::DEFAULT_CITY;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=50)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Length(max=10)
     */
    private $home;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(max=10)
     */
    private $building;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Length(max=10)
     */
    private $flat;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $area;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $comment;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({
     *      Order::STATUS_NEW,
     *	    Order::STATUS_PROCESS,
     *	    Order::STATUS_EXEC,
     *	    Order::STATUS_COMPLETE,
     *	    Order::STATUS_CANCEL
     * })
     */
    private $status = self::STATUS_NEW;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\Range(min=0, max=9999.99)
     */
    private $baseCost = 0;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\Range(min=0, max=9999.99)
     */
    private $servicesCost = 0;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\Range(min=0, max=9999.99)
     */
    private $additionalCost = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalCostComment;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    private $discountFrequency = 0;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    private $discountPromocode = 0;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\Range(min=0, max=9999.99)
     */
    private $discount;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\Range(min=0, max=9999.99)
     */
    private $paid;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Promocode", inversedBy="orders")
     */
    private $promocode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order", orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cleaner", inversedBy="orders")
     */
    private $cleaners;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->cleaners = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(int $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHome(): ?string
    {
        return $this->home;
    }

    public function setHome(string $home): self
    {
        $this->home = $home;

        return $this;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(?string $building): self
    {
        $this->building = $building;

        return $this;
    }

    public function getFlat(): ?string
    {
        return $this->flat;
    }

    public function setFlat(string $flat): self
    {
        $this->flat = $flat;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(?int $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBaseCost()
    {
        return $this->baseCost;
    }

    public function setBaseCost($baseCost): self
    {
        $this->baseCost = $baseCost;

        return $this;
    }

    public function getServicesCost()
    {
        return $this->servicesCost;
    }

    public function setServicesCost($servicesCost): self
    {
        $this->servicesCost = $servicesCost;

        return $this;
    }

    public function getAdditionalCost()
    {
        return $this->additionalCost;
    }

    public function setAdditionalCost($additionalCost): self
    {
        $this->additionalCost = $additionalCost;

        return $this;
    }

    public function getAdditionalCostComment(): ?string
    {
        return $this->additionalCostComment;
    }

    public function setAdditionalCostComment(?string $additionalCostComment): self
    {
        $this->additionalCostComment = $additionalCostComment;

        return $this;
    }

    public function getDiscountFrequency(): ?int
    {
        return $this->discountFrequency;
    }

    public function setDiscountFrequency(int $discountFrequency): self
    {
        $this->discountFrequency = $discountFrequency;

        return $this;
    }

    public function getDiscountPromocode(): ?int
    {
        return $this->discountPromocode;
    }

    public function setDiscountPromocode(int $discountPromocode): self
    {
        $this->discountPromocode = $discountPromocode;

        return $this;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getPaid()
    {
        return $this->paid;
    }

    public function setPaid($paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPromocode(): ?Promocode
    {
        return $this->promocode;
    }

    public function setPromocode(?Promocode $promocode): self
    {
        $this->promocode = $promocode;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrdr($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getOrdr() === $this) {
                $item->setOrdr(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cleaner[]
     */
    public function getCleaners(): Collection
    {
        return $this->cleaners;
    }

    public function addCleaner(Cleaner $cleaner): self
    {
        if (!$this->cleaners->contains($cleaner)) {
            $this->cleaners[] = $cleaner;
        }

        return $this;
    }

    public function removeCleaner(Cleaner $cleaner): self
    {
        if ($this->cleaners->contains($cleaner)) {
            $this->cleaners->removeElement($cleaner);
        }

        return $this;
    }
}
