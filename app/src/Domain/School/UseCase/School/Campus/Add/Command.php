<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Campus\Add;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank(message: 'Campus name should not be blank.')]
    #[Assert\Length(min: 2, minMessage: 'Campus name is too short. It should have {{ limit }} characters or more.')]
    public $name;
    public $address;
}
