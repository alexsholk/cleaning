<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Видимые отзывы
     *
     * @param int $count
     *
     * @return array
     */
    public function getVisibleReviews($count = null)
    {
        return $this->findBy([
            'visible' => true,
        ], [
            'weight'  => 'ASC',
            'createdAt' => 'DESC',
        ], $count);
    }
}
