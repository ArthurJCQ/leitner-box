<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Persistence;

use Application\Persistence\PersistenceAdapterInterface;
use Doctrine\ORM\EntityManagerInterface;

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
