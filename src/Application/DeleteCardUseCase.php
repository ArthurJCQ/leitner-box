<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\PersistenceAdapterInterface;

readonly class DeleteCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    public function execute(string $id): void
    {
        $this->cardRepository->removeCard($id);
    }
}
