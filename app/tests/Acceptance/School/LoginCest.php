<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\School;

use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;

class LoginCest extends AbstractCest
{
    protected bool $loadSchoolUser = true;

    public function login_useDefaultUser_loginSuccess(AcceptanceTester $I)
    {
        $I->amOnPage('/school/login');
        $I->dontSeeElement('[data-testid="default-layout"]');

        $I->loginAsDefaultSchool();
    }
}
