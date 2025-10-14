<?php

declare(strict_types=1);

namespace Domain;

use Domain\Exception\CannotCreateCard;
use Domain\Exception\CannotEditCard;
use Domain\Exception\CannotRemoveCard;

interface CardRepositoryInterface
{
    public function listAllCards(): iterable;

    public function findCard(string $id): ?Card;

    /** @throws CannotCreateCard */
    public function createNewCard(Card $card): void;

    /** @throws CannotEditCard */
    public function editCard(Card $card): void;

    /** @throws CannotRemoveCard */
    public function removeCard(string $id): void;

    /** @return iterable<Card> */
    public function findTodayCards(): iterable;
}
