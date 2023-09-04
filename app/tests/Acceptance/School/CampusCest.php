<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\School;

use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\Campus\Campus;
use App\Core\School\Entity\Campus\CampusId;
use App\Core\School\Entity\School\School;
use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
        $uniqueName = 'name' . (string) time();
        $I->fillField($nameField, $uniqueName);
        $I->fillField($this->id('campus-address'), $address = 'some address');
        $I->click($this->id('btn-submit'));

        $I->waitForLoaderFinishes();
        $I->dontSeeErrors();

        $I->reloadPage();
        $I->waitForLoaderFinishes();
        $I->see($uniqueName);
        $I->see($address);
    }

    public function editCampus_authAsSchoolWithExistedCampus_successEdit(AcceptanceTester $I)
    {
        $I->loadFixtures(
            new class extends Fixture {
                public function load(ObjectManager $manager): void
                {
                    $school = $manager->getRepository(School::class)->findAll()[0];
                    $campus = new Campus(
                        $school->getId(),
                        new CampusId((new UuidGenerator())->generate()),
                        'old campus',
                        'old address'
                    );
                    $manager->persist($campus);
                    $manager->flush();
                }
            }
        );
        $I->loginAsDefaultSchool();
        $I->amOnRoute(RouteEnum::SCHOOL_CAMPUS_LIST);
        $I->waitForElementVisible($btn = $this->id('btn-edit-campus'));
        $I->click($btn);
        $nameField = $this->id('campus-name');
        $I->waitForElementVisible($nameField);

        $I->fillField($nameField, $newName = 'new name');
        $I->fillField($this->id('campus-address'), $newAddress = 'new address');
        $I->click($this->id('btn-submit'));

        $I->waitForLoaderFinishes();
        $I->dontSeeErrors();

        $I->reloadPage();
        $I->waitForLoaderFinishes();
        $I->see($newName);
        $I->see($newAddress);
    }
}
