<?php

declare(strict_types=1);

namespace App\Domain\School\Common;

enum RoleEnum: string
{
    // todo user or no user, unify.
    case ADMIN_USER = 'ROLE_ADMIN';
    case SCHOOL_ADMIN = 'ROLE_SCHOOL_ADMIN';
    case SCHOOL_USER = 'ROLE_SCHOOL_USER';
    case USER = 'ROLE_USER';
    case STUDENT_USER = 'ROLE_STUDENT_USER';
}
