<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\Exception\CardEditException;

readonly class SolveCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    /** @throws CardEditException */
    public function execute(Card $card, string $answer): bool
    {
        $isCorrect = $card->isAnswerCorrect($answer);

        // Update the card based on whether the answer was correct
        $updatedCard = $isCorrect
            ? $card->handleSuccessfulAnswer()
            : $card->handleFailedAnswer();

        $this->cardRepository->editCard($updatedCard);

        return $isCorrect;
    }
}
