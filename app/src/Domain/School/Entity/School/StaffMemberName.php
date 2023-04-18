<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\School;

class StaffMemberName
{
    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}