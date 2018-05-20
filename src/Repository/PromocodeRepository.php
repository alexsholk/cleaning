<?php

namespace App\Repository;

use App\Entity\Promocode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Promocode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promocode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promocode[]    findAll()
 * @method Promocode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromocodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Promocode::class);
    }

    /**
     * Поиск активного промокода по коду
     *
     * @param $code
     * @return mixed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getActivePromocode($code)
    {
        try {
            $promocode = $this->createQueryBuilder('p')
                ->where('p.code = :code')
                ->andWhere('p.enabled = :enabled')
                ->andWhere('p.startDate <= :date OR p.startDate IS NULL')
                ->andWhere('p.endDate > :date OR p.endDate IS NULL')
                ->setParameter('code', $code)
                ->setParameter('enabled', true)
                ->setParameter('date', new \DateTime())
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $exception) {
            $promocode = null;
        }

        return $promocode;
    }

    /**
     * Поиск включенного промокода без учета сроков действия
     *
     * @param $code
     * @return null|object
     */
    public function getEnabledPromocode($code)
    {
        return $this->findOneBy([
            'code'    => $code,
            'enabled' => true,
        ]);
    }

    /**
     * Количество использованных промокодов
     *
     * @param $code
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getActivatedCount($code)
    {
        $qb = $this->createQueryBuilder('p');
        return (int)$qb->select($qb->expr()->count('o.id'))
            ->join('p.orders', 'o')
            ->where('p.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
