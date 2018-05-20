<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParamRepository")
 * @UniqueEntity("code")
 */
class Param
{
    // Категории параметров
    const CATEGORY_TEXT_BLOCKS    = 0;
    const CATEGORY_SITE_SETTINGS  = 1;
    const CATEGORY_MESSAGES       = 2;
    const CATEGORY_ORDER_SETTINGS = 3;

    const CATEGORY_CODE_TEXT_BLOCKS    = 'group.text_blocks';
    const CATEGORY_CODE_SITE_SETTINGS  = 'group.site_settings';
    const CATEGORY_CODE_MESSAGES       = 'group.messages';
    const CATEGORY_CODE_ORDER_SETTINGS = 'group.order_settings';

    public static $groups = [
        self::CATEGORY_TEXT_BLOCKS    => self::CATEGORY_CODE_TEXT_BLOCKS,
        self::CATEGORY_SITE_SETTINGS  => self::CATEGORY_CODE_SITE_SETTINGS,
        self::CATEGORY_MESSAGES       => self::CATEGORY_CODE_MESSAGES,
        self::CATEGORY_ORDER_SETTINGS => self::CATEGORY_CODE_ORDER_SETTINGS,
    ];

    // Типы параметров
    const TYPE_STRING    = 0;
    const TYPE_EMAIL     = 1;
    const TYPE_URL       = 2;
    const TYPE_PHONE     = 3;
    const TYPE_TEXT      = 4;
    const TYPE_HTML      = 5;
    const TYPE_INT       = 6;
    const TYPE_DOUBLE    = 7;
    const TYPE_BOOL      = 8;
    const TYPE_DATE      = 9;
    const TYPE_TIME      = 10;
    const TYPE_DATETIME  = 11;
    const TYPE_IMAGE_URL = 12;

    const TYPE_CODE_STRING    = 'type.string';
    const TYPE_CODE_EMAIL     = 'type.email';
    const TYPE_CODE_URL       = 'type.url';
    const TYPE_CODE_PHONE     = 'type.phone';
    const TYPE_CODE_TEXT      = 'type.text';
    const TYPE_CODE_HTML      = 'type.html';
    const TYPE_CODE_INT       = 'type.int';
    const TYPE_CODE_DOUBLE    = 'type.double';
    const TYPE_CODE_BOOL      = 'type.bool';
    const TYPE_CODE_DATE      = 'type.date';
    const TYPE_CODE_TIME      = 'type.time';
    const TYPE_CODE_DATETIME  = 'type.datetime';
    const TYPE_CODE_IMAGE_URL = 'type.image_url';

    public static $types = [
        self::TYPE_STRING    => self::TYPE_CODE_STRING,
        self::TYPE_EMAIL     => self::TYPE_CODE_EMAIL,
        self::TYPE_URL       => self::TYPE_CODE_URL,
        self::TYPE_PHONE     => self::TYPE_CODE_PHONE,
        self::TYPE_TEXT      => self::TYPE_CODE_TEXT,
        self::TYPE_HTML      => self::TYPE_CODE_HTML,
        self::TYPE_INT       => self::TYPE_CODE_INT,
        self::TYPE_DOUBLE    => self::TYPE_CODE_DOUBLE,
        self::TYPE_BOOL      => self::TYPE_CODE_BOOL,
        self::TYPE_DATE      => self::TYPE_CODE_DATE,
        self::TYPE_TIME      => self::TYPE_CODE_TIME,
        self::TYPE_DATETIME  => self::TYPE_CODE_DATETIME,
        self::TYPE_IMAGE_URL => self::TYPE_CODE_IMAGE_URL,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     * @Assert\Regex("/[A-Z0-9_]+/")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({
     *     Param::CATEGORY_TEXT_BLOCKS,
     *     Param::CATEGORY_SITE_SETTINGS,
     *     Param::CATEGORY_MESSAGES,
     *     Param::CATEGORY_ORDER_SETTINGS,
     * })
     */
    private $category;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({
     *     Param::TYPE_STRING,
     *     Param::TYPE_EMAIL,
     *     Param::TYPE_URL,
     *     Param::TYPE_PHONE,
     *     Param::TYPE_TEXT,
     *     Param::TYPE_HTML,
     *     Param::TYPE_INT,
     *     Param::TYPE_DOUBLE,
     *     Param::TYPE_BOOL,
     *     Param::TYPE_DATE,
     *     Param::TYPE_TIME,
     *     Param::TYPE_DATETIME,
     *     Param::TYPE_IMAGE_URL,
     * })
     */
    private $type;

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

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
