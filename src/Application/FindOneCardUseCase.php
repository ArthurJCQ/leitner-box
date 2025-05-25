<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;

readonly class FindOneCardUseCase
{
    public function __construct(private CardRepositoryInterface $cardRepository)
    {
    }

    public function execute(string $id): ?Card
    {
        return $this->cardRepository->findCard($id);
    }
}
