<?php

declare(strict_types=1);

namespace Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Domain\Card;
use Domain\CardRepositoryInterface;

/** @extends PostgresBaseRepository<Card> */
class PostgresCardRepositoryPostgres extends PostgresBaseRepository implements CardRepositoryInterface
{
    public function __construct(protected EntityManagerInterface $em)
    {
        parent::__construct($em, Card::class);
    }

    public function findTodayCards(): iterable
    {
        return $this->repository->createQueryBuilder('c')
            ->where('DATE_ADD(c.initialTestDate, c.delay, \'day\') <= CURRENT_DATE()')
            ->andWhere('c.active = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->toIterable();
    }
}
