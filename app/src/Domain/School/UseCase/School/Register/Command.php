<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Register;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, minMessage: 'School name is too short. It should have {{ limit }} characters or more.')]
    public $name;

    #[Assert\NotBlank]
    public $adminName;

    #[Assert\NotBlank]
    public $adminSurname;

    #[Assert\NotBlank]
    #[Assert\Email]
    public $adminEmail;
}
