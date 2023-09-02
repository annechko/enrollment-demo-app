<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\DataFixtures\AdminUserFixtures;
use App\DataFixtures\SchoolUserFixtures;
use App\Tests\AcceptanceTester;

abstract class AbstractCest
{
    protected bool $loadSchoolUser = false;
    protected bool $loadAdminUser = false;

    public function _before(AcceptanceTester $I)
    {
        if ($this->loadSchoolUser) {
            $this->doLoadSchoolUser($I);
        }
        if ($this->loadAdminUser) {
            $this->doLoadAdminUser($I);
        }
    }

    private function doLoadSchoolUser(AcceptanceTester $I): void
    {
        $I->loadFixtures($I->grabService(SchoolUserFixtures::class), false);
    }

    private function doLoadAdminUser(AcceptanceTester $I): void
    {
        $I->loadFixtures($I->grabService(AdminUserFixtures::class), false);
    }

    protected function id(string $id): string
    {
        return "[data-testid=\"$id\"]";
    }
}
