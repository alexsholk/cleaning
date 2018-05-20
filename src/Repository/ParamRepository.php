<?php

namespace App\Repository;

use App\Entity\Param;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Param|null find($id, $lockMode = null, $lockVersion = null)
 * @method Param|null findOneBy(array $criteria, array $orderBy = null)
 * @method Param[]    findAll()
 * @method Param[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParamRepository extends ServiceEntityRepository
{
    protected $params = [];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Param::class);
    }

    public function getAll(bool $reload = false): array
    {
        if ($reload || empty($this->params)) {
            $this->params = [];
            foreach ($this->findAll() as $param) {
                /** @var Param $param */
                $this->params[$param->getCode()] = $param->getValue();
            }
        }

        return $this->params;
    }

    public function get(string $code)
    {
        return $this->getAll()[$code] ?? null;
    }
}
