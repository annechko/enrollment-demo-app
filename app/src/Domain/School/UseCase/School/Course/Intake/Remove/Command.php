<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Remove;

use App\Domain\Core\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $intakeId;

    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $courseId;

    public function __construct($intakeId, $courseId)
    {
        $this->intakeId = $intakeId;
        $this->courseId = $courseId;
    }
}
