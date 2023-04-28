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

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, minMessage: 'Course name is too short. It should have {{ limit }} characters or more.')]
    public $name;
    public $description;

    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\NotBlank(),
        new Assert\Regex(pattern: UuidPattern::PATTERN_REG_EXP),
    ])]
    public $campuses;

    #[Assert\NotBlank(message: 'Start dates should not be blank.')]
    #[Assert\All([
        new Assert\Type(\DateTimeImmutable::class),
        new Assert\NotBlank(message: 'Start dates should not be blank.'),
    ])]
    public $startDates;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
