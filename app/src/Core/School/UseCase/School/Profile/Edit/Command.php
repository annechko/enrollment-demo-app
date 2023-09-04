<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Profile\Edit;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $schoolId;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $staffMemberId;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(RegexEnum::WORDS_REG_EXP, message: 'Name is not valid.')]
    #[Assert\Length(min: 2, minMessage: 'Name is too short. It should have {{ limit }} characters or more.')]
    #[Assert\Type('string')]
    public $name;
}
