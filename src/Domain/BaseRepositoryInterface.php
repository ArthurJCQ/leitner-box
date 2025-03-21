<?php

declare(strict_types=1);

namespace Domain;

/** @template T of object */
interface BaseRepositoryInterface
{
    /** @param T $object */
    public function store(object $object): void;

    public function remove (object $object): void;

    /** @return array<T> */
    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
    ): array;

    /** @return ?T */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object;
}
