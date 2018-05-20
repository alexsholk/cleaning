<?php

namespace App\Repository;

use App\Entity\Street;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Street|null find($id, $lockMode = null, $lockVersion = null)
 * @method Street|null findOneBy(array $criteria, array $orderBy = null)
 * @method Street[]    findAll()
 * @method Street[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StreetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Street::class);
    }

    /**
     * Поиск улиц по названию
     *
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function searchStreet($query = '', $limit = null)
    {
        $result = $this->createQueryBuilder('s')
            ->select('s.title')
            ->where('s.title LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('s.title', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();

        return array_column($result, 'title');
    }

    /**
     * Список улиц
     *
     * @return array
     */
    public function getStreetList()
    {
        $result = $this->createQueryBuilder('s')
            ->select('s.title')
            ->orderBy('s.title', 'ASC')
            ->getQuery()
            ->getArrayResult();

        return array_column($result, 'title');
    }
}
