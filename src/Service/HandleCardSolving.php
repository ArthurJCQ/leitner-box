<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Card;

readonly class HandleCardSolving
{
    // Test delay = number of days between 2 tests
    public const array TEST_DELAY = [1, 3, 7, 15, 30, 60];

    public function execute(Card $card, string $answer): bool
    {
        // Prevent from false-negatives
        $formattedAnswer = strtolower(trim($answer));

        // Card solving failed
        if (strtolower($card->getAnswer()) !== $formattedAnswer) {
            // Reset test delay
            $card->setInitialTestDate(new \DateTime())
                ->setDelay(self::TEST_DELAY[0]);

            return false;
        }

        // Card solving passed

        // Array key of the next test delay
        $nextDelayKey = array_search($card->getDelay(), self::TEST_DELAY, true) + 1;

        // No more test delay available: disable the Card
        if (!isset(self::TEST_DELAY[$nextDelayKey])) {
            $card->setInitialTestDate(null)
                ->setDelay(0)
                ->setActive(false);

            return true;
        }

        // Next test delay available, update Card's next test delay
        $card->setDelay(self::TEST_DELAY[$nextDelayKey]);

        return true;
    }
}
