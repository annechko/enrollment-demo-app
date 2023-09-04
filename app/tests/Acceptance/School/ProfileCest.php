<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\School;

use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;

class ProfileCest extends AbstractCest
{
    protected bool $loadSchoolUser = true;

    public function editProfile_authAsSchool_editNameSuccess(AcceptanceTester $I)
    {
        $I->loginAsDefaultSchool();
        $nameField = $this->id('profile-name');
        $I->amOnRoute(RouteEnum::SCHOOL_PROFILE);
        $I->waitForElementVisible($nameField);
        $uniqueName = 'updated school name' . (string) time();
        $I->dontSeeElement($this->id('success-msg'));

        $I->fillField($nameField, $uniqueName);
        $I->click($this->id('btn-submit'));

        $I->waitForLoaderFinishes();
        $I->waitForElementVisible($this->id('success-msg'));
        $I->reloadPage();
        $I->waitForElementVisible($nameField);
        $I->seeInField($nameField, $uniqueName);
    }
}
