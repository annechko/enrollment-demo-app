<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Add;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank(message: 'Name should not be blank.')]
    #[Assert\Length(min: 2, minMessage: 'Course name is too short. It should have {{ limit }} characters or more.')]
    public $name;
    public $description;
}
