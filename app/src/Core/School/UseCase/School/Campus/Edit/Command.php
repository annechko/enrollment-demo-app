<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Campus\Edit;

use App\Core\Common\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $id;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, minMessage: 'Campus name is too short. It should have {{ limit }} characters or more.')]
    #[Assert\Type('string')]
    public $name;

    /** @var string */
    #[Assert\Type('string')]
    public $address;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
