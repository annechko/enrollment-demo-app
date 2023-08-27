<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Course\Intake\Edit;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $intakeId;

    /** @var string */
    #[Assert\Regex(RegexEnum::ELIGIBLE_TITLE_REG_EXP, message: 'Name is not valid.')]
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
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $campusId;

    /** @var string */
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $courseId;

    public function __construct(string $intakeId, string $courseId)
    {
        $this->intakeId = $intakeId;
        $this->courseId = $courseId;
    }
}
