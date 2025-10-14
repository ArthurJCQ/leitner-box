<?php

declare(strict_types=1);

namespace Domain\Exception;

class CannotEditCard extends \Exception
{
    // phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod
    public function __construct(string $message = 'Failed to edit card', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
