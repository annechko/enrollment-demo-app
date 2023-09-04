<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Admin;

use App\Core\Common\DefaultUserEnum;
use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;

class ConfirmSchoolCest extends AbstractCest
{
    protected bool $loadAdminUser = true;

    public function schoolConfirm_newSchoolRegistered_AdminConfirmSuccess(AcceptanceTester $I)
    {
        $I->amOnRoute(RouteEnum::SCHOOL_REGISTER);
        $I->fillField($this->id('name'), 'some name');
        $I->fillField($this->id('email'), 'test@example.com');
        $I->fillField($this->id('admin-name'), 'Admin');
        $I->fillField($this->id('admin-surname'), 'Adminsurname');
        $I->click($this->id('btn-submit'));
        $I->waitForElement($this->id('success-submit'));

        $I->amOnRoute(RouteEnum::ADMIN_LOGIN);
        $I->fillField($this->id('email'), DefaultUserEnum::ADMIN_EMAIL->value);
        $I->fillField($this->id('pass'), DefaultUserEnum::ADMIN_PASS->value);
        $I->click($this->id('btn-submit'));
        $I->waitForElementVisible($this->id('default-layout'));

        $I->amOnRoute(RouteEnum::ADMIN_SCHOOL_LIST);
        $I->waitForElementNotVisible($this->id('school-list-loader'));

        $I->waitForElementVisible($this->id('confirm-btn'));
        $I->click($this->id('confirm-btn'));
        $I->waitForElementVisible($this->id('confirm-modal'));
        $I->waitForElementVisible($this->id('confirm-modal-btn'));
        $I->click($this->id('confirm-modal-btn'));
        $I->waitForElementNotVisible($this->id('confirm-modal'));
        $I->waitForElementNotVisible($this->id('school-list-loader'));

        $I->dontSeeElement($this->id('confirm-btn'));
        $I->dontSeeElement($this->id('error-msg'));
    }
}
