<?php

declare(strict_types=1);

namespace Acceptance\Admin;

use App\Core\Common\DefaultUserEnum;
use App\DataFixtures\SchoolUserFixtures;
use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;

class SchoolListCest extends AbstractCest
{
    protected bool $loadAdminUser = true;

    public function schoolConfirm_newSchoolRegistered_AdminConfirmSuccess(AcceptanceTester $I)
    {
        $I->loginAsAdmin();

        $I->amOnRoute(RouteEnum::ADMIN_SCHOOL_LIST);
        $I->waitForLoaderFinishes();
        $I->dontSeeElement($this->id('row'));
        $I->see('There are no schools.');

        $I->loadFixtures($I->grabService(SchoolUserFixtures::class));

        $I->reloadPage();
        $I->waitForLoaderFinishes();
        $I->dontSee('There are no schools.');
        $I->seeElement($this->id('row'));
        $I->see(DefaultUserEnum::SCHOOL_ADMIN_EMAIL->value, $this->id('cell-email'));
    }
}
