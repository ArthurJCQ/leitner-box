<?php

declare(strict_types=1);

namespace Domain\Exception;

class CardCreationException extends \Exception
{
    // phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod
    public function __construct(string $message = 'Failed to create card', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
