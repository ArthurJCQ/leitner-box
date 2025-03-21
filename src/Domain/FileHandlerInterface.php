<?php

declare(strict_types=1);

namespace Domain;

interface FileHandlerInterface
{
    public function handleFile(mixed $file, string $directory): string;
}
