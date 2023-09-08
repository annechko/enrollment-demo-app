<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Admin;

use App\Core\Common\DefaultUserEnum;
use App\Core\School\Entity\School\StaffMember;
use App\Infrastructure\RouteEnum;
use App\Tests\Acceptance\AbstractCest;
use App\Tests\AcceptanceTester;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $I->fillField($this->id('pass'), DefaultUserEnum::ADMIN_PASSWORD->value);
        $I->click($this->id('btn-submit'));
        $I->waitForLoaderFinishes();
        $I->waitForElementVisible($this->id('default-layout'));
        $I->waitForLoaderFinishes();
        $I->dontSeeErrors();

        $I->amOnRoute(RouteEnum::ADMIN_SCHOOL_LIST);
        $I->waitForLoaderFinishes();

        $I->waitForElementVisible($this->id('confirm-btn'));
        $I->click($this->id('confirm-btn'));
        $I->waitForLoaderFinishes();

        $I->waitForElementVisible($this->id('confirm-modal'));
        $I->waitForElementVisible($this->id('confirm-modal-btn'));
        $I->click($this->id('confirm-modal-btn'));
        $I->waitForLoaderFinishes();

        $I->reloadPage();
        $I->waitForLoaderFinishes();

        $I->dontSeeElement($this->id('confirm-btn'));
        $I->dontSeeErrors();

        $memberTester = $I->haveFriend('member');
        $memberTester->does(function (AcceptanceTester $IasMember) {
            /** @var StaffMember $member */
            $member = $IasMember->grabEntityFromRepository(StaffMember::class);
            $url = $IasMember->grabService(UrlGeneratorInterface::class)->generate(
                RouteEnum::SCHOOL_MEMBER_REGISTER,
                [
                    'invitationToken' => $member->getInvitationToken()->getValue(),
                    'schoolId' => $member->getSchoolId()->getValue(),
                ],
            );
            $IasMember->amOnPage($url);
            $IasMember->waitForElementVisible($this->id('member-password'));
            $IasMember->fillField($this->id('member-password'), $pass = '3487nd9283d4n');
            $IasMember->click($this->id('btn-submit'));
            $IasMember->waitForLoaderFinishes();
            $IasMember->dontSeeErrors();
            $IasMember->waitForElementVisible($this->id('success-msg'));

            $IasMember->loginAsSchool($member->getEmail()->getValue(), $pass);
            $IasMember->waitForLoaderFinishes();
            $IasMember->dontSeeErrors();
        });
    }
}
