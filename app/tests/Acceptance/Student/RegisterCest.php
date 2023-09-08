<?php

declare(strict_types=1);

namespace Acceptance\Student;

use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;

class RegisterCest extends AbstractCest
{
    public function register_loginWithoutEmailVerification_seeError(AcceptanceTester $I)
    {
        $I->amOnRoute(RouteEnum::STUDENT_REGISTER);
        $I->dontSeeElement($this->id('default-layout'));
        $I->dontSeeErrors();
        $I->fillField($this->id('email'), $email = 'not-verified-email@example.com');
        $I->fillField($this->id('pass'), $pass = 'password');
        $I->fillField($this->id('name'), 'name');
        $I->fillField($this->id('surname'), 'surname');
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();
        $I->dontSeeErrors();
        $I->seeElement($this->id('success-msg'));

        $I->amOnRoute(RouteEnum::STUDENT_LOGIN);
        $I->fillField($this->id('email'), $email);
        $I->fillField($this->id('pass'), $pass);
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();
        $I->seeElement($this->id('error-msg'));
        $I->see('email not verified', $this->id('error-msg'));
    }
}
