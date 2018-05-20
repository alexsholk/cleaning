<?php

namespace App\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Promocode;
use App\Entity\Session;
use App\Repository\PromocodeRepository;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PromocodeModel
{
    /** @var PromocodeRepository */
    protected $promocodeRepository;
    /** @var SessionRepository */
    protected $sessionRepository;
    /** @var SessionInterface */
    protected $session;

    public function __construct(ManagerRegistry $registry, SessionInterface $session)
    {
        $this->promocodeRepository = $registry->getRepository(Promocode::class);
        $this->sessionRepository   = $registry->getRepository(Session::class);
        $this->session             = $session;
    }

    /**
     * Количество доступных промокодов
     *
     * @param $code
     * @return bool|int|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAvailableCount($code)
    {
        // Поиск активного промокода с учетом сроков действия
        $promocode = $this->promocodeRepository->getActivePromocode($code);
        if (!$promocode instanceof Promocode) {
            return false;
        }

        $quantity  = $promocode->getQuantity();
        $activated = $this->promocodeRepository->getActivatedCount($code);
        $reserved  = $this->getReservedCount($code);
        $available = $quantity - $activated - $reserved;

        return $available;
    }

    /**
     * Количество промокодов, зарезервированных другими пользователями
     *
     * @param $code
     *
     * @return int
     */
    public function getReservedCount($code)
    {
        $sessionId = $this->session->getId();
        $sessions  = $this->sessionRepository->getActiveSessions($sessionId);
        $reserved  = 0;

        foreach ($sessions as $session) {
            /** @var Session $session */
            $order = $session->get('order');
            if (isset($order['promocode']) && mb_strtoupper($order['promocode']) == mb_strtoupper($code)) {
                $reserved++;
            }
        }

        return $reserved;
    }

    /**
     * Репозиторий
     *
     * @param $class
     *
     * @return PromocodeRepository|SessionRepository
     */
    public function getRepository($class)
    {
        switch ($class) {
            case Promocode::class:
                return $this->promocodeRepository;
                break;
            case Session::class:
                return $this->sessionRepository;
                break;
            default:
                throw new \InvalidArgumentException('Only PromocodeRepository and SessionRepository are available.');
        }
    }
}