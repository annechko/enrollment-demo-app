<?php

declare(strict_types=1);

namespace App\Domain\Student\Entity\Student;

use Webmozart\Assert\Assert;

class StudentId
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
}
