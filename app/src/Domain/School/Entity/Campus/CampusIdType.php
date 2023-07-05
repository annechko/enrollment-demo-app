<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\Campus;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class CampusIdType extends GuidType
{
    public const NAME = 'school_campus_id';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof CampusId ? $value->getValue() : null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CampusId
    {
        return !empty($value) ? new CampusId($value) : null;
    }
}
