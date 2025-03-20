<?php

declare(strict_types=1);

namespace Application\Storage;

interface FileHandlerInterface
{
    public function handleFile(mixed $file, string $directory): string;
}
