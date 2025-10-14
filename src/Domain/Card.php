<?php

declare(strict_types=1);

namespace Domain;

/**
 * Immutable domain object representing a Card in the Leitner box system
 */
readonly class Card
{
    /**
     * Delay schedule for the Leitner box system (in days)
     */
    private const array TEST_DELAY = [1, 3, 7, 15, 30, 60];

    public function __construct(
        public string $question,
        public string $answer,
        public ?\DateTimeInterface $initialTestDate,
        public ?bool $active,
        public int $delay = 1,
        public ?string $id = null,
    ) {
    }

    public static function create(
        string $question,
        string $answer,
        \DateTimeInterface $initialTestDate,
        bool $active,
        int $delay = 1,
        ?string $id = null,
    ): self {
        return new self($question, $answer, $initialTestDate, $active, $delay, $id);
    }

    /**
     * Creates a new Card with the updated delay
     *
     * @throws \InvalidArgumentException. If delay is negative.
     */
    public function withDelay(int $delay): self
    {
        if ($delay < 0) {
            throw new \InvalidArgumentException('Delay cannot be negative');
        }

        return new self(
            $this->question,
            $this->answer,
            $this->initialTestDate,
            $this->active,
            $delay,
            $this->id,
        );
    }

    /**
     * Creates a new Card with the updated initial test date
     */
    public function withInitialTestDate(?\DateTimeInterface $initialTestDate): self
    {
        return new self(
            $this->question,
            $this->answer,
            $initialTestDate,
            $this->active,
            $this->delay,
            $this->id,
        );
    }

    /**
     * Creates a new Card with the updated active status
     */
    public function withActive(bool $active): self
    {
        return new self(
            $this->question,
            $this->answer,
            $this->initialTestDate,
            $active,
            $this->delay,
            $this->id,
        );
    }

    /**
     * Checks if the provided answer is correct for this card
     */
    public function isAnswerCorrect(string $answer): bool
    {
        return strtolower(trim($answer)) === strtolower($this->answer);
    }

    /**
     * Resolves the provided answer to the card
     */
    public function resolve(string $answer): self
    {
        if ($this->isAnswerCorrect($answer)) {
            return $this->handleSuccessfulAnswer();
        }

        return $this->handleFailedAnswer();
    }

    /**
     * Checks if this card is due for testing today
     */
    public function isDueForTesting(): bool
    {
        if (!$this->active) {
            return false;
        }

        if (!$this->initialTestDate instanceof \DateTime) {
            return false;
        }

        $dueDate = (clone $this->initialTestDate)->modify($this->delay . 'days');

        return $dueDate <= new \DateTime('today');
    }

    /**
     * Handles a failed answer attempt by resetting the card's delay and setting the initial test date to now
     */
    private function handleFailedAnswer(): self
    {
        return $this->withInitialTestDate(new \DateTime())
            ->withDelay(1);
    }

    /**
     * Handles a successful answer attempt by incrementing the card's delay according to the Leitner box system
     * If the card has reached the maximum delay, it will be deactivated
     */
    private function handleSuccessfulAnswer(): self
    {
        $nextDelayKey = array_search($this->delay, self::TEST_DELAY, true) + 1;

        if (!isset(self::TEST_DELAY[$nextDelayKey])) {
            return $this->withInitialTestDate(null)
                ->withDelay(0)
                ->withActive(false);
        }

        return $this->withDelay(self::TEST_DELAY[$nextDelayKey]);
    }
}
