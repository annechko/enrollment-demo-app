<?php

declare(strict_types=1);

namespace App\Domain\Student\Entity\Application;

use Webmozart\Assert\Assert;

class ApplicationId
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
