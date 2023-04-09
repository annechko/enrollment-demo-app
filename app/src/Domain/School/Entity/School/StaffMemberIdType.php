<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\School;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class StaffMemberIdType extends GuidType
{
    public const NAME = 'school_staff_member_id';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof StaffMemberId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?StaffMemberId
    {
        return !empty($value) ? new StaffMemberId($value) : null;
    }
}
