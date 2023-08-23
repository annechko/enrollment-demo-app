<?php

declare(strict_types=1);

namespace App\Core\Common;

class RegexEnum
{
    public const UUID_PATTERN = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';
    public const UUID_PATTERN_REG_EXP = '#' . self::UUID_PATTERN . '#';
    public const UUID_PATTERN_WITH_TEMPLATE = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}|([:a-zA-Z]+){1}';
    public const WORDS_REG_EXP = '#\w+#';
    public const WORDS_AND_NUMBERS_REG_EXP = '#[\w0-9]+#';
    public const ADDRESS_REG_EXP = '#^[#.0-9a-zA-Z\s,-]+$#';
}
