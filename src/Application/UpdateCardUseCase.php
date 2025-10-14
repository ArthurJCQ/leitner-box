<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\Exception\CannotEditCard;

readonly class UpdateCardUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    /** @throws CannotEditCard */
    public function execute(Card $card): void
    {
        $this->cardRepository->editCard($card);
    }
}
