<?php

declare(strict_types=1);

namespace Application;

use Domain\CardRepositoryInterface;
use Domain\Exception\CannotRemoveCard;

readonly class DeleteCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    /** @throws CannotRemoveCard */
    public function execute(string $id): void
    {
        $this->cardRepository->removeCard($id);
    }
}
