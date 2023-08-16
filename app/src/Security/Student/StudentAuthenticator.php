<?php

declare(strict_types=1);

namespace App\Security\Student;

use App\Infrastructure\RouteEnum;
use App\Security\AbstractAuthenticator;

class StudentAuthenticator extends AbstractAuthenticator
{
    private const LOGIN_ROUTE = RouteEnum::STUDENT_LOGIN;
    private const AFTER_LOGIN_ROUTE = RouteEnum::STUDENT_HOME;

    protected function getLoginRoute(): string
    {
        return self::LOGIN_ROUTE;
    }

    protected function getAfterLoginRoute(): string
    {
        return self::AFTER_LOGIN_ROUTE;
    }
}
