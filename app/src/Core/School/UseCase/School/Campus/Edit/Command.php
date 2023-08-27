<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Campus\Edit;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $id;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(RegexEnum::ELIGIBLE_TITLE_REG_EXP, message: 'Name is not valid.')]
    #[Assert\Length(min: 2, minMessage: 'Campus name is too short. It should have {{ limit }} characters or more.')]
    #[Assert\Type('string')]
    public $name;

    /** @var string */
    #[Assert\Type('string')]
    #[Assert\Regex(RegexEnum::ADDRESS_REG_EXP, message: 'Address is not valid.')]
    public $address;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
