<?php

declare(strict_types=1);

namespace App\Core\School\Entity\Course\Intake;

use Webmozart\Assert\Assert;

class IntakeId
{
    public function __construct(private readonly string $value)
    {
        Assert::stringNotEmpty($this->value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isSameValue(string $value): bool
    {
        return $this->value === $value;
    }
}
