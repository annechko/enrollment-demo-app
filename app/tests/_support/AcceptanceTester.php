<?php

declare(strict_types=1);
namespace App\Tests;

use App\Core\Common\DefaultUserEnum;
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
        $this->amOnPage('/school/login');

        $this->fillField('[data-testid="email"]', DefaultUserEnum::SCHOOL_ADMIN_EMAIL->value);
        $this->fillField('[data-testid="pass"]', DefaultUserEnum::SCHOOL_ADMIN_PASS->value);
        $this->click('[data-testid="submit-btn"]');

        $this->waitForElement('[data-testid="default-layout"]');
    }

    public function amOnRoute(string $routeName): void
    {
        /** @var UrlGeneratorInterface $generator */
        $generator = $this->grabService(UrlGeneratorInterface::class);
        $this->amOnPage($generator->generate($routeName));
    }
}
