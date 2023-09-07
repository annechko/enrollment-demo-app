<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\School;

use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\Campus\Campus;
use App\Core\School\Entity\Campus\CampusId;
use App\Core\School\Entity\Course\Course;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Entity\Course\Intake\IntakeId;
use App\Core\School\Entity\School\School;
use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IntakeCest extends AbstractCest
{
    protected bool $loadSchoolUser = true;

    public function haveCourseAndCampuses_addIntake_success(AcceptanceTester $I)
    {
        $I->loadFixtures(
            new class extends Fixture {
                public function load(ObjectManager $manager): void
                {
                    $school = $manager->getRepository(School::class)->findAll()[0];
                    $manager->persist(
                        new Campus(
                            $school->getId(),
                            new CampusId((new UuidGenerator())->generate()),
                            'school campus 1',
                            'school campus address 1'
                        )
                    );
                    $manager->persist(
                        new Campus(
                            $school->getId(),
                            new CampusId((new UuidGenerator())->generate()),
                            'school campus 2',
                            'school campus address 2'
                        )
                    );

                    $manager->persist(
                        new Course(
                            $school->getId(),
                            new CourseId((new UuidGenerator())->generate()),
                            'school course name',
                            'school course descr',
                        )
                    );
                    $manager->flush();
                }
            }
        );
        $I->loginAsDefaultSchool();

        $I->amOnRoute(RouteEnum::SCHOOL_COURSE_LIST);
        $I->waitForLoaderFinishes();
        $I->click($this->id('btn-edit-course'));
        $I->waitForLoaderFinishes();
        $I->waitForElementVisible($this->id('btn-add-intake'));
        $I->click($this->id('btn-add-intake'));
        $I->waitForElementVisible($this->id('intake-modal'));
        $I->waitForElementVisible($this->id('intake-form'));
        $I->waitForElementVisible($this->id('select-campus'));
        $I->click($this->id('select-campus'));
        $I->see('school campus 1', 'option');
        $I->see('school campus 2', 'option');
        $I->fillField($this->id('intake-name'), $name = 'intake name');
        // todo add normal datepicker and remove this js.
        $I->executeJS('document.getElementById("startDate").valueAsDate = new Date()');
        $I->executeJS('document.getElementById("endDate").valueAsDate = new Date()');
        $I->selectOption($this->id('select-campus'), 'school campus 1');
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();

        $I->dontSeeErrors();
        $I->reloadPage();
        $I->waitForLoaderFinishes();
        $I->see($name, $this->id('cell-intake-name'));
        $I->dontSee('0', $this->id('cell-intake-class-size'));
    }

    public function haveCourse_addIntakeWith0ClassSize_seeZero(AcceptanceTester $I)
    {
        $I->loadFixtures(
            new class extends Fixture {
                public function load(ObjectManager $manager): void
                {
                    $school = $manager->getRepository(School::class)->findAll()[0];
                    $manager->persist(
                        new Course(
                            $school->getId(),
                            new CourseId((new UuidGenerator())->generate()),
                            'school course name',
                            'school course descr',
                        )
                    );
                    $manager->flush();
                }
            }
        );
        $I->loginAsDefaultSchool();

        $I->amOnRoute(RouteEnum::SCHOOL_COURSE_LIST);
        $I->waitForLoaderFinishes();
        $I->click($this->id('btn-edit-course'));
        $I->waitForLoaderFinishes();
        $I->waitForElementVisible($this->id('btn-add-intake'));
        $I->click($this->id('btn-add-intake'));
        $I->waitForElementVisible($this->id('intake-modal'));
        $I->waitForElementVisible($this->id('intake-form'));
        $I->fillField($this->id('intake-class-size'), '0');
        // todo add normal datepicker and remove this js.
        $I->executeJS('document.getElementById("startDate").valueAsDate = new Date()');
        $I->executeJS('document.getElementById("endDate").valueAsDate = new Date()');
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();

        $I->dontSeeErrors();
        $I->reloadPage();
        $I->waitForLoaderFinishes();
        $I->see('0', $this->id('cell-intake-class-size'));
    }

    public function haveIntake_editIntake_success(AcceptanceTester $I)
    {
        $I->loadFixtures(
            new class extends Fixture {
                public function load(ObjectManager $manager): void
                {
                    $school = $manager->getRepository(School::class)->findAll()[0];
                    $manager->persist(
                        $course = new Course(
                            $school->getId(),
                            new CourseId((new UuidGenerator())->generate()),
                            'school course name',
                            'school course descr',
                        )
                    );
                    $manager->persist(
                        $campus = new Campus(
                            $school->getId(),
                            new CampusId((new UuidGenerator())->generate()),
                            'school campus',
                            'school campus address'
                        )
                    );
                    $course->addIntake(
                        new IntakeId((new UuidGenerator())->generate()),
                        new \DateTimeImmutable('2020-01-01'),
                        new \DateTimeImmutable('2021-01-01'),
                    );
                    $manager->flush();
                }
            }
        );
        $I->loginAsDefaultSchool();

        $I->amOnRoute(RouteEnum::SCHOOL_COURSE_LIST);
        $I->waitForLoaderFinishes();
        $I->click($this->id('btn-edit-course'));
        $I->waitForLoaderFinishes();
        $I->waitForElementVisible($this->id('btn-edit-intake'));

        $I->see('2020-01-01', $this->id('cell-intake-start'));
        $I->see('2021-01-01', $this->id('cell-intake-end'));

        $I->click($this->id('btn-edit-intake'));
        $I->waitForElementVisible($this->id('intake-modal'));
        $I->waitForElementVisible($this->id('intake-form'));
        $I->waitForElementVisible($this->id('intake-class-size'));
        $I->seeInField($this->id('intake-start'), '2020-01-01');
        $I->seeInField($this->id('intake-end'), '2021-01-01');

        $I->fillField($this->id('intake-class-size'), $newSize = '11');
        $I->fillField($this->id('intake-name'), $newName = 'updated');
        // todo add normal datepicker and remove this js.
        $I->executeJS('document.getElementById("startDate").valueAsDate = new Date("2025")');
        $I->executeJS('document.getElementById("endDate").valueAsDate = new Date("2026")');
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();

        $I->dontSeeErrors();
        $I->reloadPage();
        $I->waitForLoaderFinishes();
        $I->see($newSize, $this->id('cell-intake-class-size'));
        $I->see($newName, $this->id('cell-intake-name'));
        $I->see('2025', $this->id('cell-intake-start'));
        $I->see('2026', $this->id('cell-intake-end'));
    }
}
