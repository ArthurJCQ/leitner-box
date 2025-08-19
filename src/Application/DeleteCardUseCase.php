<?php

declare(strict_types=1);

namespace Application;

use Domain\CardRepositoryInterface;
use Domain\Exception\CardRemovalException;

readonly class DeleteCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    /** @throws CardRemovalException */
    public function execute(string $id): void
    {
        $this->cardRepository->removeCard($id);
    }
}
