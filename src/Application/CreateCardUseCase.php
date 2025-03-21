<?php

declare(strict_types=1);

namespace Application;

use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\FileHandlerInterface;
use Domain\PersistenceAdapterInterface;

class CreateCardUseCase
{
    public function __construct(
        private readonly FileHandlerInterface $fileHandler,
        private readonly CardRepositoryInterface $cardRepository,
        private readonly PersistenceAdapterInterface $persistenceAdapter,
    ) {
    }

    public function handle(Card $card, mixed $imgFile, ?string $existingImg = null): void
    {
        $newFilename = null;

        if ($imgFile instanceof UploadedFile) {
            $newFilename = $this->fileHandler->handleFile($imgFile, 'cardCovers');
        }

        $card->setImage($newFilename ?? $existingImg);

        $this->cardRepository->store($card);
        $this->persistenceAdapter->flush();
    }
}
