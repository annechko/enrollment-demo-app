<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Confirm;

use App\Core\Common\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank()]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
