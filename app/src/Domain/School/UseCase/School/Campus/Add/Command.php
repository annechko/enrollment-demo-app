<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Campus\Add;

use App\Domain\Core\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank(message: 'Campus name should not be blank.')]
    #[Assert\Length(min: 2, minMessage: 'Campus name is too short. It should have {{ limit }} characters or more.')]
    public $name;

    /** @var string */
    #[Assert\Type('string')]
    public $address;

    /** @var string */
    #[Assert\NotBlank(message: 'SchoolId should not be blank.')]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $schoolId;

    public function __construct(string $schoolId)
    {
        $this->schoolId = $schoolId;
    }
}
