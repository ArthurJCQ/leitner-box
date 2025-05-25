<?php

declare(strict_types=1);

namespace Domain;

interface CardRepositoryInterface
{
    public function listAllCards(): iterable;

    public function findCard(string $id): ?Card;

    public function createNewCard(
        string $question,
        string $answer,
        \DateTimeInterface $initialTestDate,
        bool $active,
    ): void;

    public function editCard(
        string $id,
        string $question,
        string $answer,
        \DateTimeInterface $initialTestDate,
        bool $active,
    ): void;

    public function removeCard(string $id): void;

    public function solveCard(string $id, int $delay, \DateTimeInterface $initialTestDate, bool $active): void;

    /** @return iterable<Card> */
    public function findTodayCards(): iterable;
}
