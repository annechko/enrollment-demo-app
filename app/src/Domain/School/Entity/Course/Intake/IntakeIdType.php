<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\Course\Intake;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class IntakeIdType extends GuidType
{
    public const NAME = 'school_course_intake_id';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof IntakeId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?IntakeId
    {
        return !empty($value) ? new IntakeId($value) : null;
    }
}
