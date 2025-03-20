<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Domain\Entity\Card;
use Domain\Repository\CardRepositoryInterface;

/** @extends BaseRepositoryDoctrine<Card> */
class CardRepositoryDoctrine extends BaseRepositoryDoctrine implements CardRepositoryInterface
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
