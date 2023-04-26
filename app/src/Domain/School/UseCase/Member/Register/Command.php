<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\Member\Register;

use App\Domain\Core\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $schoolId;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $invitationToken;

    #[Assert\NotBlank]
    public $plainPassword;

    public function __construct($schoolId, $invitationToken)
    {
        $this->schoolId = $schoolId;
        $this->invitationToken = $invitationToken;
    }
}
