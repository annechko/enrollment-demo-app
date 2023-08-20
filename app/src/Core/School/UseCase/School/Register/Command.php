<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Register;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, minMessage: 'School name is too short. It should have {{ limit }} characters or more.')]
    public $name;

    /** @var string */
    #[Assert\NotBlank]
    public $adminName;

    /** @var string */
    #[Assert\NotBlank]
    public $adminSurname;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Email]
    public $adminEmail;
}
