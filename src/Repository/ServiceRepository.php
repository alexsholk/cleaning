<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    const SERVICE_ROOM = 'ROOM';
    const SERVICE_BATHROOM = 'BATHROOM';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * Базовые услуги - уборка комнаты и санузла
     *
     * @return array
     */
    public function getBasicServices()
    {
        return $this->findBy([
            'code'      => [self::SERVICE_ROOM, self::SERVICE_BATHROOM],
            'available' => true,
        ], [
            'weight' => 'ASC',
        ]);
    }

    /**
     * Уборка комнат
     *
     * @return null|object
     */
    public function getServiceRoom()
    {
        return $this->findOneBy(['code' => self::SERVICE_ROOM, 'available' => true]);
    }

    /**
     * Уборка санузла
     *
     * @return null|object
     */
    public function getServiceBathroom()
    {
        return $this->findOneBy(['code' => self::SERVICE_BATHROOM, 'available' => true]);
    }

    /**
     * Дополнительные услуги
     *
     * @return array
     */
    public function getAdditionalServices()
    {
        return $this->findBy([
            'code'      => null,
            'available' => true,
        ], [
            'weight' => 'ASC',
        ]);
    }

    /**
     * Услуга
     *
     * @param $id
     * @return null|object
     */
    public function getAvailableById($id)
    {
        return $this->findOneBy([
            'id'        => $id,
            'available' => true,
        ]);
    }
}
