<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Profile\Edit;

use App\Domain\Common\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $schoolId;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $staffMemberId;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, minMessage: 'Name is too short. It should have {{ limit }} characters or more.')]
    #[Assert\Type('string')]
    public $name;

    public function __construct(string $schoolId, string $staffMemberId,)
    {
        $this->schoolId = $schoolId;
        $this->staffMemberId = $staffMemberId;
    }
}
