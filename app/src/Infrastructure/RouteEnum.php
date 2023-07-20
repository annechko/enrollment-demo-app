<?php

declare(strict_types=1);

namespace App\Infrastructure;

// todo move all routes here
// todo move all paths and params too?
class RouteEnum
{
    public const HOME = 'app_home';

    public const ADMIN_HOME = 'admin_home';
    public const ADMIN_LOGIN = 'admin_login';
    public const ADMIN_LOGOUT = 'admin_logout';

    public const SCHOOL_HOME = 'school_home';
    public const SCHOOL_REGISTER = 'school_register';
    public const SCHOOL_LOGIN = 'school_login';
    public const SCHOOL_LOGOUT = 'school_logout';

    public const STUDENT_HOME = 'student_home';
    public const STUDENT_REGISTER = 'student_register';
    public const STUDENT_LOGIN = 'student_login';
    public const STUDENT_LOGOUT = 'student_logout';
    public const API_STUDENT_REGISTER = 'api_student_register';
    public const API_STUDENT_LOGIN = 'api_student_login';

}
