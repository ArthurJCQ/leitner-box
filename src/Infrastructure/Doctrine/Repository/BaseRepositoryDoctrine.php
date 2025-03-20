<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Repository\BaseRepositoryInterface;

/** @template T of object */
abstract class BaseRepositoryDoctrine implements BaseRepositoryInterface
{
    /** @var EntityRepository<T> */
    protected EntityRepository $repository;

    /** @param class-string<T> $className */
    public function __construct(protected EntityManagerInterface $entityManager, string $className)
    {
        $this->repository = $this->entityManager->getRepository($className);
    }

    /** @param T $object */
    public function store(object $object): void
    {
        $this->entityManager->persist($object);
    }

    public function remove(object $object): void
    {
        $this->entityManager->remove($object);
    }

    /** @return array<T> */
    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
    ): array {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /** @return ?T */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }
}
