<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;

readonly class CreateCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    public function execute(Card $card): void
    {
        $this->cardRepository->createNewCard(
            $card->question,
            $card->answer,
            $card->initialTestDate,
            $card->active,
        );
    }
}
