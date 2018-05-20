<?php

namespace App\Repository;

use App\Entity\CallRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CallRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method CallRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method CallRequest[]    findAll()
 * @method CallRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CallRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CallRequest::class);
    }

//    /**
//     * @return CallRequest[] Returns an array of CallRequest objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CallRequest
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
