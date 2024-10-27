<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function findTodayCards(): iterable
    {
        return $this->createQueryBuilder('c')
            ->where('DATE_ADD(c.initialTestDate, c.delay, \'day\') <= CURRENT_DATE()')
            ->andWhere('c.active = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->toIterable();
    }
}
