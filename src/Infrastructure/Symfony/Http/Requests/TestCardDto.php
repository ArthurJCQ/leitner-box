<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Http\Requests;

/**
 * DTO for returning card data for testing, exposing only id and question
 */
readonly class TestCardDto
{
    public function __construct(
        public string $id,
        public string $question,
    ) {
    }
}
