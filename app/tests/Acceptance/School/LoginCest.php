<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\School;

use App\Core\Common\DefaultUserEnum;
use App\Tests\AcceptanceTester;

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function login_useDefaultUser_loginSuccess(AcceptanceTester $I)
    {
        $I->amOnPage('/school/login');
        $I->dontSeeElement('[data-test-id="default-layout"]');

        $I->fillField('[data-testid="email"]', DefaultUserEnum::SCHOOL_ADMIN_EMAIL->value);
        $I->fillField('[data-testid="pass"]', DefaultUserEnum::SCHOOL_ADMIN_PASS->value);
        $I->click('[data-testid="submitBtn"]');

        $I->waitForElement('[data-test-id="default-layout"]');
    }
}
