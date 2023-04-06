<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\Member\Register;

use App\Domain\Core\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    private const PATTERN = '#' . UuidPattern::PATTERN . '#';

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: self::PATTERN)]
    public $schoolId;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: self::PATTERN)]
    public $invitationToken;

    #[Assert\NotBlank]
    public $plainPassword;

    public function __construct($schoolId, $invitationToken,)
    {
        $this->schoolId = $schoolId;
        $this->invitationToken = $invitationToken;
    }
}
