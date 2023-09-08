<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Infrastructure\RouteEnum;
use App\Tests\AcceptanceTester;

class HomeCest
{
    public function testHome_asUnauthorized_seeTitle(AcceptanceTester $I)
    {
        $I->amOnRoute(RouteEnum::HOME);
        $I->see('Enrollment Demo Application');
    }
}
