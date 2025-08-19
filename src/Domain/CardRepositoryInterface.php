<?php

declare(strict_types=1);

namespace Domain;

use Domain\Exception\CardCreationException;
use Domain\Exception\CardEditException;
use Domain\Exception\CardRemovalException;

interface CardRepositoryInterface
{
    public function listAllCards(): iterable;

    public function findCard(string $id): ?Card;

    /** @throws CardCreationException */
    public function createNewCard(Card $card): void;

    /** @throws CardEditException */
    public function editCard(Card $card): void;

    /** @throws CardRemovalException */
    public function removeCard(string $id): void;

    /** @return iterable<Card> */
    public function findTodayCards(): iterable;
}
