<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\School;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class SchoolIdType extends GuidType
{
	public const NAME = 'school_id';

	public function getName(): string
	{
		return static::NAME;
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform): string
	{
		return $value instanceof SchoolId ? $value->getValue() : $value;
	}

	public function convertToPHPValue($value, AbstractPlatform $platform): ?SchoolId
	{
		return !empty($value) ? new SchoolId($value) : null;
	}
}
