<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Edit;

use App\Domain\Core\UuidPattern;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    private const PATTERN = '#' . UuidPattern::PATTERN . '#';

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: self::PATTERN)]
    public $id;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, minMessage: 'Course name is too short. It should have {{ limit }} characters or more.')]
    public $name;
    public $description;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
