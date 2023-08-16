<?php

declare(strict_types=1);

namespace App\Security\School;

use App\Infrastructure\RouteEnum;
use App\Security\AbstractAuthenticator;

class SchoolStaffMemberAuthenticator extends AbstractAuthenticator
{
    private const LOGIN_ROUTE = RouteEnum::SCHOOL_LOGIN;
    private const AFTER_LOGIN_ROUTE = RouteEnum::SCHOOL_HOME;

    protected function getLoginRoute(): string
    {
        return self::LOGIN_ROUTE;
    }

    protected function getAfterLoginRoute(): string
    {
        return self::AFTER_LOGIN_ROUTE;
    }
}
