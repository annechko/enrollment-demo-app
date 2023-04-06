<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Register;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $name;
    /**
     * @Assert\NotBlank()
     */
    public $adminName;
    /**
     * @Assert\NotBlank()
     */
    public $adminSurname;
    /**
     * @Assert\NotBlank()
     */
    public $adminEmail;
}
