<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Storage;

use Application\Storage\FileHandlerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileHandler implements FileHandlerInterface
{
    public function __construct(
        private SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/files')] private string $fileDirectory,
    ) {
    }

    /** @param UploadedFile $file */
    public function handleFile(mixed $file, string $directory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();

        $file->move(sprintf('%s/%s', $this->fileDirectory, $directory), $newFilename);

        return $newFilename;
    }
}
