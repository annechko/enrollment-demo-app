<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\Member\Register;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $schoolId;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $invitationToken;

    /** @var string */
    #[Assert\NotBlank]
    public $plainPassword;

    public function __construct(string $schoolId, string $invitationToken)
    {
        $this->schoolId = $schoolId;
        $this->invitationToken = $invitationToken;
    }
}
