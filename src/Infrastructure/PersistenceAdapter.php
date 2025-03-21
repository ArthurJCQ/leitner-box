<?php

declare(strict_types=1);

namespace Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Domain\PersistenceAdapterInterface;

readonly class PersistenceAdapter implements PersistenceAdapterInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
