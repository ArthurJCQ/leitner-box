<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\Exception\CardCreationException;

readonly class CreateCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    /** @throws CardCreationException */
    public function execute(Card $card): void
    {
        $this->cardRepository->createNewCard($card);
    }
}
