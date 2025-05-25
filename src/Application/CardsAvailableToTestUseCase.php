<?php

declare(strict_types=1);

namespace Application;

use Domain\CardRepositoryInterface;

readonly class CardsAvailableToTestUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    public function execute(): array
    {
        $cards = [];

        foreach ($this->cardRepository->findTodayCards() as $card) {
            if ($card->isDueForTesting()) {
                $cards[] = $card;
            }
        }

        return $cards;
    }
}
