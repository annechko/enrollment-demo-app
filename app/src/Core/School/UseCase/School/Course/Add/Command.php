<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Course\Add;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank(message: 'Name should not be blank.')]
    #[Assert\Regex(RegexEnum::ELIGIBLE_TITLE_REG_EXP, message: 'Name is not valid.')]
    #[Assert\Length(min: 2, minMessage: 'Course name is too short. It should have {{ limit }} characters or more.')]
    public $name;

    /** @var string */
    #[Assert\Type('string')]
    #[Assert\Regex(RegexEnum::DESCRIPTION_REG_EXP, message: 'Description is not valid.')]
    public $description;

    /** @var string */
    #[Assert\NotBlank(message: 'SchoolId should not be blank.')]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $schoolId;

    public function __construct(string $schoolId)
    {
        $this->schoolId = $schoolId;
    }
}
