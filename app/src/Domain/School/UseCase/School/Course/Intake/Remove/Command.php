<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Remove;

use App\Domain\Core\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $intakeId;

    /** @var string */
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $courseId;

    public function __construct(string $intakeId, string $courseId)
    {
        $this->intakeId = $intakeId;
        $this->courseId = $courseId;
    }
}
