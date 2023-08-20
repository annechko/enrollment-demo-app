<?php

declare(strict_types=1);

namespace App\ReadModel\Student\Filter;

use App\Domain\Common\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class IntakeFilter
{
    /**
     * @var string
     */
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP, message: 'SchoolId value is not valid.')]
    public $schoolId;

    /**
     * @var string
     */
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP, message: 'CourseId value is not valid.')]
    public $courseId;

    public function __construct(string $schoolId, string $courseId)
    {
        $this->schoolId = $schoolId;
        $this->courseId = $courseId;
    }
}
