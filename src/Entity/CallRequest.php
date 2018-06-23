<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CallRequestRepository")
 */
class CallRequest
{
    // Статусы обработки запроса
    const STATUS_NEW = 0;
    const STATUS_PHONED = 1;
    const STATUS_NOT_AVAILABLE = 2;
    const STATUS_WRONG_PHONE = 3;
    const STATUS_SPAM = 4;

    const STATUS_CODE_NEW = 'status.new';
    const STATUS_CODE_PHONED = 'status.phoned';
    const STATUS_CODE_NOT_AVAILABLE = 'status.not_available';
    const STATUS_CODE_WRONG_PHONE = 'status.wrong_phone';
    const STATUS_CODE_SPAM = 'status.spam';

    public static $statuses = [
        self::STATUS_NEW => self::STATUS_CODE_NEW,
        self::STATUS_PHONED => self::STATUS_CODE_PHONED,
        self::STATUS_NOT_AVAILABLE => self::STATUS_CODE_NOT_AVAILABLE,
        self::STATUS_WRONG_PHONE => self::STATUS_CODE_WRONG_PHONE,
        self::STATUS_SPAM => self::STATUS_CODE_SPAM,
    ];

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
     * @ORM\Column(type="ip")
     */
    private $ip;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({
     *      CallRequest::STATUS_NEW,
     *	    CallRequest::STATUS_PHONED,
     *	    CallRequest::STATUS_NOT_AVAILABLE,
     *	    CallRequest::STATUS_WRONG_PHONE,
     *	    CallRequest::STATUS_SPAM
     * })
     */
    private $status = self::STATUS_NEW;

    public function getId(): ?int
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

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip): self
    {
        $this->ip = $ip;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    public function getStatusName(): ?string
    {
        return self::$statuses[$this->status];
    }
}
