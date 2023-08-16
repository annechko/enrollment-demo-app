<?php

declare(strict_types=1);

namespace App\Security\Admin;

use App\Infrastructure\RouteEnum;
use App\Security\AbstractAuthenticator;

class AdminAuthenticator extends AbstractAuthenticator
{
    private const LOGIN_ROUTE = RouteEnum::ADMIN_LOGIN;
    private const AFTER_LOGIN_ROUTE = RouteEnum::ADMIN_HOME;

    protected function getLoginRoute(): string
    {
        return self::LOGIN_ROUTE;
    }

    protected function getAfterLoginRoute(): string
    {
        return self::AFTER_LOGIN_ROUTE;
    }
}
