<?php

declare(strict_types=1);

namespace App\Core\School\Entity\School;

class StaffMemberName
{
    public function __construct(
        private readonly string $firstName,
        private readonly string $surname,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}
