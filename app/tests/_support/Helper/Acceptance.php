<?php

declare(strict_types=1);
namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    public static function selector(string $id): string
    {
        return "[data-testid=\"$id\"]";
    }
}
