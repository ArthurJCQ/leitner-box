<?php

declare(strict_types=1);

namespace Application\Persistence;

interface PersistenceAdapterInterface
{
    public function flush(): void;
}
