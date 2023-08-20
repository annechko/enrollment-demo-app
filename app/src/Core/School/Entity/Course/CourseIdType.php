<?php

declare(strict_types=1);

namespace App\Core\School\Entity\Course;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class CourseIdType extends GuidType
{
    public const NAME = 'school_course_id';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof CourseId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CourseId
    {
        return !empty($value) ? new CourseId($value) : null;
    }
}
