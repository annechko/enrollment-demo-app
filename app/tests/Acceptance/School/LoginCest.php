<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\School;

use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;

class LoginCest extends AbstractCest
{
    protected bool $loadSchoolUser = true;

    public function login_useUnexistedUser_seeError(AcceptanceTester $I)
    {
        $I->amOnPage('/school/login');
        $I->dontSeeElement($this->id('default-layout'));
        $I->dontSeeErrors();
        $I->fillField($this->id('email'), 'no-such-user@example.com');
        $I->fillField($this->id('pass'), 'password');
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();

        $I->seeElement($this->id('error-msg'));
    }

    public function login_useDefaultUser_loginSuccess(AcceptanceTester $I)
    {
        $I->amOnPage('/school/login');
        $I->dontSeeElement($this->id('default-layout'));

        $I->loginAsDefaultSchool();
    }
}
