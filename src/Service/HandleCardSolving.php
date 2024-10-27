<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Card;

readonly class HandleCardSolving
{
    public const array TEST_DELAY = [1, 3, 7, 15, 30, 60];

    public function execute(Card $card, string $answer): bool
    {
        $formattedAnswer = strtolower(trim($answer));

        // Card solving failed
        if (strtolower($card->getAnswer()) !== $formattedAnswer) {
            $card->setInitialTestDate(new \DateTime())
                ->setDelay(1);

            return false;
        }

        // Card solving passed
        $nextDelayKey = array_search($card->getDelay(), self::TEST_DELAY, true) + 1;

        if (!isset(self::TEST_DELAY[$nextDelayKey])) {
            $card->setInitialTestDate(null)
                ->setDelay(0)
                ->setActive(false);

            return true;
        }

        $card->setDelay(self::TEST_DELAY[$nextDelayKey]);

        return true;
    }
}
