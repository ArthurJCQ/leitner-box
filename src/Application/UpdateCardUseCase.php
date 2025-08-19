<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\Exception\CardEditException;

readonly class UpdateCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    /** @throws CardEditException */
    public function execute(Card $card): void
    {
        $this->cardRepository->editCard($card);
    }
}
