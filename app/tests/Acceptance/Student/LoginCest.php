<?php

declare(strict_types=1);

namespace Acceptance\Student;

use App\Core\Common\DefaultUserEnum;
use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;

class LoginCest extends AbstractCest
{
    protected bool $loadStudentUser = true;

    public function login_useUnexistedUser_seeError(AcceptanceTester $I)
    {
        $I->amOnRoute(RouteEnum::STUDENT_LOGIN);
        $I->dontSeeElement($this->id('default-layout'));
        $I->dontSeeErrors();
        $I->fillField($this->id('email'), 'no-such-student@example.com');
        $I->fillField($this->id('pass'), 'password');
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();

        $I->seeElement($this->id('error-msg'));
    }

    public function login_useDefaultUser_loginSuccess(AcceptanceTester $I)
    {
        $I->amOnRoute(RouteEnum::STUDENT_LOGIN);
        $I->dontSeeElement($this->id('default-layout'));
        $I->dontSeeErrors();
        $I->fillField($this->id('email'), DefaultUserEnum::STUDENT_EMAIL->value);
        $I->fillField($this->id('pass'), DefaultUserEnum::STUDENT_PASSWORD->value);
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();
        $I->dontSeeErrors();
        $I->waitForElementVisible($this->id('default-layout'));
        $I->dontSeeErrors();
    }
}
