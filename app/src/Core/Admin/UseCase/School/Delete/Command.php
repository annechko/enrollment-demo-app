<?php

declare(strict_types=1);

namespace App\Core\Admin\UseCase\School\Delete;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank()]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $schoolId;
}
