<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\AcceptanceTester;

class HomeCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function testHome_seeTitle(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Enrollment Demo Application');
    }
}
