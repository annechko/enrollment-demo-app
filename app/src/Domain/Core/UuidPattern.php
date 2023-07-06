<?php

declare(strict_types=1);

namespace App\Domain\Core;

class UuidPattern
{
    public const PATTERN = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';
    public const PATTERN_REG_EXP = '#' . self::PATTERN . '#';
    public const PATTERN_WITH_TEMPLATE = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}|([:a-zA-Z]+){1}';
}
