<?php

declare(strict_types=1);

namespace App\Core\Student\Entity\Application;

enum ApplicationStatusEnum: string
{
    case STATUS_NEW = 'NEW';

    public static function new(): self
    {
        return self::STATUS_NEW;
    }
}