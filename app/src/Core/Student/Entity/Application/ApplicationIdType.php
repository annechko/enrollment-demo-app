<?php

declare(strict_types=1);

namespace App\Core\Student\Entity\Application;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class ApplicationIdType extends GuidType
{
    public const NAME = 'student_application_id';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof ApplicationId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ApplicationId
    {
        return !empty($value) ? new ApplicationId($value) : null;
    }
}
