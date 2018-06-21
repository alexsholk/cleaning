<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 * @UniqueEntity("title")
 * @UniqueEntity("code")
 * @UniqueEntity("shortCode")
 * @Vich\Uploadable
 */
class Service
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
    private $title;

    /**
     * @ORM\Column(type="string", length=20, unique=true, nullable=true)
     * @Assert\Regex("/[A-Z0-9_]{3,20}/")
     */
    private $code;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\Range(min=0, max=999.99)
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\Column(type="boolean")
     */
    private $countable;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=1, nullable=true)
     * @Assert\Range(max=999.9)
     */
    private $minCount = 1;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=1, nullable=true)
     * @Assert\Range(max=999.9)
     */
    private $maxCount;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=1, nullable=true)
     * @Assert\Range(min=0.1, max=99.9)
     */
    private $step;

    /**
     * @ORM\Column(type="smallint")
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=0, max=100)
     */
    private $unit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=3)
     * @Assert\Regex("/^[A-Za-z]{1,3}$/")
     */
    private $shortCode;

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
     * Файл изображения
     *
     * @Vich\UploadableField(mapping="service_image", fileNameProperty="icon")
     * @Assert\Image(mimeTypes="image/png", maxSize="200k", minWidth=24, minHeight=24, maxWidth=512, maxHeight=512)
     */
    protected $imageFile;

    /**
     * @param File $image
     * @return Service
     */
    public function setImageFile(File $image = null)
    {
        if ($this->imageFile = $image) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Формы слова единицы измерения
     *
     * @return array
     */
    public function getUnitForms()
    {
        return preg_split('/[^\\w]+/ui', $this->unit);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

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

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getCountable(): ?bool
    {
        return $this->countable;
    }

    public function setCountable(bool $countable): self
    {
        $this->countable = $countable;

        return $this;
    }

    public function getMinCount()
    {
        return $this->minCount;
    }

    public function setMinCount($minCount): self
    {
        $this->minCount = $minCount;

        return $this;
    }

    public function getMaxCount()
    {
        return $this->maxCount;
    }

    public function setMaxCount($maxCount): self
    {
        $this->maxCount = $maxCount;

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

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    public function setShortCode(string $shortCode): self
    {
        $this->shortCode = $shortCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return Service
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return Service
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function __toString()
    {
        return (string)$this->title;
    }
}
