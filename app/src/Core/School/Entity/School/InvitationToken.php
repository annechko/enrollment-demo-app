<?php

declare(strict_types=1);

namespace App\Core\School\Entity\School;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable()]
class InvitationToken
{
    private const MAX_LIFETIME = 'P5D';

    #[ORM\Column(type: Types::STRING, length: 36, nullable: true)]
    /**
     * @phpstan-ignore property.onlyWritten
     *
     * @phpstan-ignore-next-line
     */
    private readonly string $value;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $value, \DateTimeImmutable $createdAt)
    {
        Assert::stringNotEmpty($value);
        Assert::maxLength($value, 255);
        $this->value = $value;
        $this->createdAt = $createdAt;
    }

    public function isExpired(): bool
    {
        $now = new \DateTimeImmutable();

        return $now > $this->createdAt->add(new \DateInterval(self::MAX_LIFETIME));
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
