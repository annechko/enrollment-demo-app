<?php

declare(strict_types=1);

namespace App\Core\School\Entity\School;

use App\Core\Common\UuidPattern;
use Webmozart\Assert\Assert;

class SchoolId
{
    public function __construct(private readonly string $value)
    {
        Assert::stringNotEmpty($this->value);
        Assert::regex($this->value, UuidPattern::PATTERN_REG_EXP);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
