<?php

declare(strict_types=1);

namespace App\Core\Student\UseCase\Student\Register;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Name should not be blank.')]
    #[Assert\Regex(RegexEnum::WORDS_REG_EXP, message: 'Name is not valid.')]
    #[Assert\Length(min: 1, minMessage: 'Name is too short. It should have {{ limit }} characters or more.')]
    public $name;

    /** @var string */
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Surname should not be blank.')]
    #[Assert\Regex(RegexEnum::WORDS_REG_EXP, message: 'Surname is not valid.')]
    #[Assert\Length(min: 1, minMessage: 'Surname is too short. It should have {{ limit }} characters or more.')]
    public $surname;

    /** @var string */
    #[Assert\NotBlank(message: 'Email should not be blank.')]
    #[Assert\Email]
    public $email;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 4096, minMessage: 'Your password should be at least {{ limit }} characters')]
    public $plainPassword;
}
