<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Register;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Regex(RegexEnum::WORDS_REG_EXP, message: 'Name is not valid.')]
    #[Assert\Length(min: 2, minMessage: 'School name is too short. It should have {{ limit }} characters or more.')]
    public $name;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(RegexEnum::WORDS_REG_EXP, message: 'Admin name is not valid.')]
    public $adminName;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(RegexEnum::WORDS_REG_EXP, message: 'Admin surname is not valid.')]
    public $adminSurname;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Email]
    public $adminEmail;
}
