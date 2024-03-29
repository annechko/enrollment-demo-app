<?php

declare(strict_types=1);

namespace App\Core\Common;

use Doctrine\ORM\EntityManagerInterface;

class Flusher
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
