<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\Entity\Card;

/** @extends BaseRepositoryInterface<Card> */
interface CardRepositoryInterface extends BaseRepositoryInterface
{
    public function findTodayCards(): iterable;
}
