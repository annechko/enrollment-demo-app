<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Edit;

use App\Domain\Common\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $intakeId;

    /** @var string */
    public $name;

    /** @var \DateTimeImmutable */
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\NotBlank(message: 'Start date should not be blank.')]
    public $startDate;

    /** @var \DateTimeImmutable */
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\NotBlank(message: 'End date should not be blank.')]
    public $endDate;

    /** @var int */
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero(message: 'Class size should be positive.')]
    public $classSize;

    /** @var string */
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $campusId;

    /** @var string */
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $courseId;

    public function __construct(string $intakeId, string $courseId)
    {
        $this->intakeId = $intakeId;
        $this->courseId = $courseId;
    }
}
