<?php

declare(strict_types=1);

namespace App\Domain\Student\Entity\Student;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class StudentIdType extends GuidType
{
    public const NAME = 'student_id';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof StudentId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?StudentId
    {
        return !empty($value) ? new StudentId($value) : null;
    }
}
