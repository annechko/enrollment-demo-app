<?php

declare(strict_types=1);

namespace App\Core\Common;

enum DefaultUserEnum: string
{
    case SCHOOL_ADMIN_EMAIL = 'school@example.com';
    case SCHOOL_ADMIN_PASSWORD = 'school';
    case ADMIN_EMAIL = 'admin@example.com';
    case ADMIN_PASSWORD = 'admin';
    case STUDENT_EMAIL = 'student@example.com';
    case STUDENT_PASSWORD = 'student';

    public static function isDefaultSchoolUser(string $email): bool
    {
        return $email === self::SCHOOL_ADMIN_EMAIL->value;
    }
}
