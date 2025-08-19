<?php

declare(strict_types=1);

namespace Domain\Exception;

class CardRemovalException extends \Exception
{
    // phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod
    public function __construct(string $message = 'Failed to remove card', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
