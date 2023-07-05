<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Add;

use App\Domain\Core\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public $name;

    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\NotBlank(message: 'Start date should not be blank.')]
    public $startDate;

    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\NotBlank(message: 'End date should not be blank.')]
    public $endDate;

    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero(message: 'Class size should be positive.')]
    public $classSize;

    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $campusId;

    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $courseId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }
}
