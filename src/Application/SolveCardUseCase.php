<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\PersistenceAdapterInterface;

readonly class SolveCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    public function execute(Card $card, string $answer): bool
    {
        $isCorrect = $card->isAnswerCorrect($answer);

        // Update the card based on whether the answer was correct
        $updatedCard = $isCorrect
            ? $card->handleSuccessfulAnswer()
            : $card->handleFailedAnswer();

        $this->cardRepository->solveCard(
            $updatedCard->id,
            $updatedCard->delay,
            $updatedCard->initialTestDate,
            $updatedCard->active,
        );

        return $isCorrect;
    }
}
