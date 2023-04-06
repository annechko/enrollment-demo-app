<?php

declare(strict_types=1);

namespace App\Domain\Core;

class UuidPattern
{
    public const PATTERN = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';
}