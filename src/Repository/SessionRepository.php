<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Session::class);
    }

    /**
     * Активные сессии
     *
     * @param $excludeId - игнорировать сессии с этими id
     * @return mixed
     */
    public function getActiveSessions($excludeId = null)
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.sessTime + s.sessLifetime > :now')
            ->setParameter('now', time())
            ->orderBy('s.sessTime', 'DESC');

        if ($excludeId) {
            $qb->andWhere('s.sessId NOT IN (:ids)')
                ->setParameter('ids', (array)$excludeId);
        }

        return $qb->getQuery()->getResult();
    }
}
