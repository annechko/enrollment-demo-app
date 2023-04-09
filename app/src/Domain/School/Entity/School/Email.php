<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\School;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable()]
class Email
{
    #[ORM\Column(name: 'email', type: Types::STRING, length: 320)]
    private readonly string $value;

    public function __construct(string $value)
    {
        Assert::stringNotEmpty($value);
        Assert::email($value);
        Assert::maxLength($value, 320);

        $this->value = mb_strtolower($value);
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
