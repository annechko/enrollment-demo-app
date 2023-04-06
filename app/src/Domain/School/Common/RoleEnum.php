<?php

declare(strict_types=1);

namespace App\Domain\School\Common;

enum RoleEnum: string
{
    case USER = 'ROLE_USER'; // todo probably delete
    case ADMIN = 'ROLE_ADMIN';
    case SCHOOL_USER = 'ROLE_SCHOOL_USER';
    case SCHOOL_ADMIN = 'ROLE_SCHOOL_ADMIN';
}