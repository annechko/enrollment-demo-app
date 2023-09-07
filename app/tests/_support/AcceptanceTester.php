<?php

declare(strict_types=1);
namespace App\Tests;

use App\Core\Common\DefaultUserEnum;
use App\Infrastructure\RouteEnum;
use App\Tests\Helper\Acceptance;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    use \Codeception\Lib\Actor\Shared\Friend;

    /**
     * Define custom actions here
     */
    public function screen(bool $useTimestampInName = false): void
    {
        $this->wait(2);
        if ($useTimestampInName === true) {
            $suffix = (new \DateTimeImmutable)->format('y-m-d_H-i-s');
        } else {
            $suffix = '';
        }
        $this->makeScreenshot('screen' . $suffix);
    }

    public function loginAsDefaultSchool(): void
    {
        $this->loginAsSchool(
            DefaultUserEnum::SCHOOL_ADMIN_EMAIL->value,
            DefaultUserEnum::SCHOOL_ADMIN_PASS->value
        );
    }

    public function loginAsSchool(string $email, string $pass): void
    {
        $this->amOnRoute(RouteEnum::SCHOOL_LOGIN);

        $this->fillField(Acceptance::selector('email'), $email);
        $this->fillField(Acceptance::selector('pass'), $pass);
        $this->click(Acceptance::selector('btn-submit'));
        $this->waitForLoaderFinishes();

        $this->waitForElement(Acceptance::selector('default-layout'));
    }

    public function loginAsAdmin(): void
    {
        $this->amOnRoute(RouteEnum::ADMIN_LOGIN);
        $this->fillField(Acceptance::selector('email'), DefaultUserEnum::ADMIN_EMAIL->value);
        $this->fillField(Acceptance::selector('pass'), DefaultUserEnum::ADMIN_PASS->value);
        $this->click(Acceptance::selector('btn-submit'));
        $this->waitForLoaderFinishes();
        $this->dontSeeErrors();
        $this->waitForElementVisible(Acceptance::selector('default-layout'));
        $this->waitForLoaderFinishes();
        $this->dontSeeErrors();
    }

    public function amOnRoute(string $routeName): void
    {
        /** @var UrlGeneratorInterface $generator */
        $generator = $this->grabService(UrlGeneratorInterface::class);
        $this->amOnPage($generator->generate($routeName));
    }

    public function waitForLoaderFinishes(): void
    {
        $this->waitForElementNotVisible(Acceptance::selector('data-loader'));
        $this->wait(1);
    }

    public function dontSeeErrors(): void
    {
        $this->dontSeeElement(Acceptance::selector('error-msg'));
    }
}
