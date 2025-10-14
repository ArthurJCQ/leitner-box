<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\Exception\CannotCreateCard;

readonly class CreateCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    /** @throws CannotCreateCard */
    public function execute(Card $card): void
    {
        $this->cardRepository->createNewCard($card);
    }
}
