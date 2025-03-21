<?php

declare(strict_types=1);

namespace Domain;

interface PersistenceAdapterInterface
{
    public function flush(): void;
}
