<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\Exception\CannotEditCard;

readonly class SolveCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    /** @throws CannotEditCard */
    public function execute(Card $card, string $answer): bool
    {
        // Update the card based on whether the answer was correct
        $updatedCard = $card->resolve($answer);

        $this->cardRepository->editCard($updatedCard);

        return $card->isAnswerCorrect($answer);
    }
}
