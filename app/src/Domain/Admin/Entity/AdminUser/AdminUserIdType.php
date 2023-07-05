<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entity\AdminUser;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class AdminUserIdType extends GuidType
{
    public const NAME = 'admin_user_id';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof AdminUserId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?AdminUserId
    {
        return !empty($value) ? new AdminUserId($value) : null;
    }
}
