<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\DataFixtures\SchoolUserFixtures;
use App\Tests\AcceptanceTester;

abstract class AbstractCest
{
    protected bool $loadSchoolUser = false;

    public function _before(AcceptanceTester $I)
    {
        if ($this->loadSchoolUser) {
            $this->doLoadSchoolUser($I);
        }
    }

    private function doLoadSchoolUser(AcceptanceTester $I): void
    {
        $I->loadFixtures($I->grabService(SchoolUserFixtures::class), false);
    }
}
