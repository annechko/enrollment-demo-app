<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Campus\Add;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank(message: 'Campus name should not be blank.')]
    #[Assert\Regex(RegexEnum::WORDS_AND_NUMBERS_REG_EXP, message: 'Name is not valid.')]
    #[Assert\Length(min: 2, minMessage: 'Campus name is too short. It should have {{ limit }} characters or more.')]
    public $name;

    /** @var string */
    #[Assert\Type('string')]
    #[Assert\Regex(RegexEnum::ADDRESS_REG_EXP, message: 'Address is not valid.')]
    public $address;

    /** @var string */
    #[Assert\NotBlank(message: 'SchoolId should not be blank.')]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $schoolId;

    public function __construct(string $schoolId)
    {
        $this->schoolId = $schoolId;
    }
}
