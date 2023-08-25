<?php

declare(strict_types=1);

namespace App\Core\Common;

class RegexEnum
{
    private const UUID_PATTERN_CORE = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';
    public const UUID_PATTERN = '^' . self::UUID_PATTERN_CORE . '$';
    public const UUID_PATTERN_REG_EXP = '#' . self::UUID_PATTERN . '#';
    public const UUID_PATTERN_WITH_TEMPLATE = '^' . self::UUID_PATTERN_CORE . '|([:a-zA-Z]+){1}$';
    public const WORDS_REG_EXP = '#^[\w \-\(\),]+$#';
    public const WORDS_AND_NUMBERS_REG_EXP = '#^[\w0-9 \-\(\),]+$#';
    public const ADDRESS_REG_EXP = '#^[#.0-9a-zA-Z\s,-]+$#';
    public const DESCRIPTION_REG_EXP = '#^[\w0-9 \-,\.]+$#i';
}
