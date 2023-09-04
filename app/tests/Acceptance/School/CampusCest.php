<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\School;

use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;

class CampusCest extends AbstractCest
{
    protected bool $loadSchoolUser = true;

    public function addCampus_authAsSchool_success(AcceptanceTester $I)
    {
        $I->loginAsDefaultSchool();
        $nameField = $this->id('campus-name');

        $I->amOnRoute(RouteEnum::SCHOOL_CAMPUS_LIST);
        $I->waitForElementVisible($this->id('btn-add-campus'));
        $I->click($this->id('btn-add-campus'));

        $I->waitForElementVisible($nameField);
        $uniqueName = 'updated school name' . (string) time();
        $I->fillField($nameField, $uniqueName);
        $I->fillField($this->id('campus-address'), $address = 'some address');
        $I->click($this->id('btn-submit'));

        $I->waitForElementNotVisible($this->id('data-loader'));
        $I->dontSeeElement($this->id('error-msg'));

        $I->reloadPage();
        $I->waitForElementNotVisible($this->id('data-loader'));
        $I->see($uniqueName);
        $I->see($address);
    }
}
