<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\DataFixtures\AdminUserFixtures;
use App\DataFixtures\SchoolUserFixtures;
use App\Tests\AcceptanceTester;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class AbstractCest
{
    protected bool $loadSchoolUser = false;
    protected bool $loadAdminUser = false;

    public function _before(AcceptanceTester $I)
    {
        $I->loadFixtures(
            new class extends Fixture {
                public function load(ObjectManager $manager)
                {
                    // that way we clean all tables _data_ before every test.
                    // yeah, I know there is a Db module, but it deletes all _tables_.
                }
            },
            false
        );

        if ($this->loadSchoolUser) {
            $this->doLoadSchoolUser($I);
        }
        if ($this->loadAdminUser) {
            $this->doLoadAdminUser($I);
        }
    }

    private function doLoadSchoolUser(AcceptanceTester $I): void
    {
        $I->loadFixtures($I->grabService(SchoolUserFixtures::class));
    }

    private function doLoadAdminUser(AcceptanceTester $I): void
    {
        $I->loadFixtures($I->grabService(AdminUserFixtures::class));
    }

    protected function id(string $id): string
    {
        return "[data-testid=\"$id\"]";
    }
}
