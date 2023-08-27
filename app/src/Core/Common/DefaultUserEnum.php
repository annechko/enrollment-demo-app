<?php

declare(strict_types=1);

namespace App\Core\Common;

enum DefaultUserEnum: string
{
    case SCHOOL_ADMIN_EMAIL = 'school@example.com';

    public static function isDefaultSchoolUser(string $email): bool
    {
        return $email === self::SCHOOL_ADMIN_EMAIL->value;
    }
}
