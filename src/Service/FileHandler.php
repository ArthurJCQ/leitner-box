<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileHandler
{
    public function __construct(
        private SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/files')] private string $fileDirectory,
    ) {
    }

    public function handleFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();

        $file->move($this->fileDirectory, $newFilename);

        return $newFilename;
    }
}
