<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Http\Requests;

use Domain\Card;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Card::class)]
readonly class UpdateCardRequest
{
    public function __construct(
        public ?string $id = null,
        #[Assert\NotBlank(message: 'La question ne peut pas être vide')]
        public string $question = '',
        #[Assert\NotBlank(message: 'La réponse ne peut pas être vide')]
        public string $answer = '',
        public ?\DateTimeInterface $initialTestDate = null,
        public bool $active = true,
    ) {
    }
}
