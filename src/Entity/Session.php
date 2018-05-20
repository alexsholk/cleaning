<?php

namespace App\Entity;

use App\Tools\SessionHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Session
{
    /**
     * @var AttributeBag
     */
    private $bag;

    /**
     * @ORM\Id()
     * @ORM\Column(name="sess_id", type="string", length=128)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sessId;

    /**
     * @ORM\Column(name="sess_data", type="blob")
     */
    private $sessData;

    /**
     * @ORM\Column(name="sess_time", type="integer")
     */
    private $sessTime;

    /**
     * @ORM\Column(name="sess_lifetime", type="integer")
     */
    private $sessLifetime;

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->bag = new AttributeBag();
        $data      = is_resource($this->sessData) ? stream_get_contents($this->sessData) : $this->sessData;
        $data      = SessionHelper::unserialize($data);

        if (is_array($data[$this->bag->getStorageKey()])) {
            $this->bag->initialize($data[$this->bag->getStorageKey()]);
        }
    }

    /**
     * Get session value
     *
     * @param $name
     * @param null $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return $this->bag->get($name, $default);
    }

    /**
     * Get all session values
     *
     * @return array
     */
    public function all()
    {
        return $this->bag->all();
    }

    public function getSessId(): ?string
    {
        return $this->sessId;
    }

    public function setSessId(string $sessId): self
    {
        $this->sessId = $sessId;

        return $this;
    }

    public function setSessData($sessData): self
    {
        $this->sessData = $sessData;

        return $this;
    }

    public function getSessTime(): ?int
    {
        return $this->sessTime;
    }

    public function setSessTime(int $sessTime): self
    {
        $this->sessTime = $sessTime;

        return $this;
    }

    public function getSessLifetime(): ?int
    {
        return $this->sessLifetime;
    }

    public function setSessLifetime(int $sessLifetime): self
    {
        $this->sessLifetime = $sessLifetime;

        return $this;
    }

    public function getSessData()
    {
        return $this->sessData;
    }
}
