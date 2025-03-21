<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\PersistenceAdapterInterface;

readonly class SolveCardUseCase
{
    public const array TEST_DELAY = [1, 3, 7, 15, 30, 60];

    public function __construct(
        private PersistenceAdapterInterface $persistenceAdapter,
    ) {
    }

    public function execute(Card $card, string $answer): bool
    {
        $formattedAnswer = strtolower(trim($answer));

        // Card solving failed
        if (strtolower($card->answer) !== $formattedAnswer) {
            $card->setInitialTestDate(new \DateTime())
                ->setDelay(1);

            return false;
        }

        // Card solving passed
        $nextDelayKey = array_search($card->delay, self::TEST_DELAY, true) + 1;

        if (!isset(self::TEST_DELAY[$nextDelayKey])) {
            $card->setInitialTestDate(null)
                ->setDelay(0)
                ->setActive(false);

            return true;
        }

        $card->setDelay(self::TEST_DELAY[$nextDelayKey]);

        $this->persistenceAdapter->flush();

        return true;
    }
}
