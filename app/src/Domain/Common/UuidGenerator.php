<?php

declare(strict_types=1);

namespace App\Domain\Common;

use Symfony\Component\Uid\Ulid;

class UuidGenerator
{
    public function generate(): string
    {
        return (new Ulid(Ulid::generate()))->toRfc4122();
    }
}
