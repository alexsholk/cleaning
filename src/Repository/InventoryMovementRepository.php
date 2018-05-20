<?php

namespace App\Repository;

use App\Entity\InventoryMovement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InventoryMovement|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventoryMovement|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventoryMovement[]    findAll()
 * @method InventoryMovement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventoryMovementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InventoryMovement::class);
    }

//    /**
//     * @return InventoryMovement[] Returns an array of InventoryMovement objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InventoryMovement
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
