<?php

declare(strict_types=1);

namespace Domain;

/** @extends BaseRepositoryInterface<Card> */
interface CardRepositoryInterface extends BaseRepositoryInterface
{
    public function findTodayCards(): iterable;
}
