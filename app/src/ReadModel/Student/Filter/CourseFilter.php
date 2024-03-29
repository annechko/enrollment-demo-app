<?php

declare(strict_types=1);

namespace App\ReadModel\Student\Filter;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CourseFilter
{
    /**
     * @var string
     */
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP, message: 'SchoolId value is not valid.')]
    public $schoolId;

    /**
     * @var string
     */
    public $name;

    public function __construct(string $schoolId)
    {
        $this->schoolId = $schoolId;
    }
}
