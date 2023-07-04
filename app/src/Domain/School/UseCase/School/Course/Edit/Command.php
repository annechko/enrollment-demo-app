<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Edit;

use App\Domain\Core\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP)]
    public $id;

    #[Assert\NotBlank(message: 'Name should not be blank.')]
    #[Assert\Length(min: 2, minMessage: 'Course name is too short. It should have {{ limit }} characters or more.')]
    public $name;
    public $description;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
